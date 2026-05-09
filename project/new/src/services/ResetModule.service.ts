import axios from 'axios';
import { XMLParser } from 'fast-xml-parser';
import { psGetAllIds, psDelete } from '../utils/prestashop-api';
import { getExecutionOrder, RESET_MODULES } from '../config/reset-modules.config';
import type {
  ResetApiResource,
  ResetError,
  ResetModuleReport,
  ResetProgress,
  ResetReport,
  ResetResourceReport,
} from '../types/reset.types';

// ---------------------------------------------------------------------------
// Callbacks
// ---------------------------------------------------------------------------

export type ResetCallbacks = {
  /** Called whenever overall progress changes. */
  onProgress?: (progress: ResetProgress) => void;

  /** Called when a single item is successfully deleted. */
  onItemSuccess?: (moduleId: string, resource: string, itemId: string | number) => void;

  /** Called when a single item deletion fails. */
  onItemError?: (moduleId: string, resource: string, itemId: string | number, message: string) => void;

  /** Called when a resource starts being processed. */
  onResourceStart?: (moduleId: string, resource: string, total: number) => void;

  /** Called when a module starts being processed. */
  onModuleStart?: (moduleId: string) => void;

  /** Called when a module finishes processing. */
  onModuleComplete?: (moduleId: string, report: ResetModuleReport) => void;
};

// ---------------------------------------------------------------------------
// Service
// ---------------------------------------------------------------------------

/**
 * Generic service that handles resetting (deleting) data via the PrestaShop
 * XML API.  It supports pause / resume / cancel and emits granular progress.
 *
 * Usage:
 * ```ts
 * const svc = new ResetModuleService();
 * const report = await svc.run(['orders', 'customers'], { onProgress });
 * ```
 */
export class ResetModuleService {
  private paused = false;
  private cancelled = false;
  private resumePromise: Promise<void> | null = null;
  private resumeResolver: (() => void) | null = null;

  // ---- control ----

  pause(): void {
    if (this.paused) return;
    this.paused = true;
    this.resumePromise = new Promise((resolve) => {
      this.resumeResolver = resolve;
    });
  }

  resume(): void {
    if (!this.paused) return;
    this.paused = false;
    this.resumeResolver?.();
    this.resumeResolver = null;
    this.resumePromise = null;
  }

  cancel(): void {
    this.cancelled = true;
    this.resume(); // unblock if paused
  }

  // ---- main entry ----

  /**
   * Execute the reset for the given module IDs.
   *
   * 1. Resolve execution order (topological sort based on dependencies).
   * 2. For each module, iterate over its API resources (in configured order).
   * 3. For each resource: list all IDs then delete one by one.
   */
  async run(moduleIds: string[], callbacks: ResetCallbacks = {}): Promise<ResetReport> {
    const startedAt = new Date().toISOString();
    const orderedIds = getExecutionOrder(moduleIds);

    const allErrors: ResetError[] = [];
    const moduleReports: ResetModuleReport[] = [];

    // --- Phase 1: count all items across selected modules ---
    const progress: ResetProgress = {
      status: 'counting',
      currentModuleId: null,
      currentResource: null,
      percent: 0,
      totalItems: 0,
      processedItems: 0,
      resourceProgress: [],
    };
    callbacks.onProgress?.(cloneProgress(progress));

    // Pre-fetch all IDs per resource so we know totals up-front
    const idMap = new Map<string, (string | number)[]>();

    for (const modId of orderedIds) {
      const mod = RESET_MODULES[modId];
      if (!mod) continue;

      for (const res of mod.apiResources) {
        const ids = await psGetAllIds(res.apiResource, res.singularTag);
        const key = `${modId}::${res.apiResource}`;
        idMap.set(key, ids);
        progress.totalItems += ids.length;

        progress.resourceProgress.push({
          apiResource: res.apiResource,
          total: ids.length,
          processed: 0,
          success: 0,
          failed: 0,
        });
      }
    }

    // --- Phase 2: delete ---
    progress.status = 'running';
    callbacks.onProgress?.(cloneProgress(progress));

    for (const modId of orderedIds) {
      if (this.cancelled) break;

      const mod = RESET_MODULES[modId];
      if (!mod) continue;

      callbacks.onModuleStart?.(modId);
      progress.currentModuleId = modId;

      const resourceReports: ResetResourceReport[] = [];

      for (const res of mod.apiResources) {
        if (this.cancelled) break;

        const key = `${modId}::${res.apiResource}`;
        const ids = idMap.get(key) || [];

        progress.currentResource = res.apiResource;
        callbacks.onResourceStart?.(modId, res.apiResource, ids.length);

        let deleted = 0;
        let failed = 0;

        for (const id of ids) {
          if (this.cancelled) break;

          // Wait if paused
          if (this.paused && this.resumePromise) {
            await this.resumePromise;
          }

          try {
            await psDelete(res.apiResource, String(id));
            deleted += 1;
            callbacks.onItemSuccess?.(modId, res.apiResource, id);
          } catch (error) {
            failed += 1;
            const message = extractErrorMessage(error);
            allErrors.push({
              moduleId: modId,
              apiResource: res.apiResource,
              itemId: id,
              message,
            });
            callbacks.onItemError?.(modId, res.apiResource, id, message);
          }

          progress.processedItems += 1;
          progress.percent =
            progress.totalItems === 0
              ? 100
              : Math.round((progress.processedItems / progress.totalItems) * 100);

          // Update per-resource progress
          const rp = progress.resourceProgress.find((r) => r.apiResource === res.apiResource);
          if (rp) {
            rp.processed += 1;
            rp.success = deleted;
            rp.failed = failed;
          }

          callbacks.onProgress?.(cloneProgress(progress));
        }

        resourceReports.push({
          apiResource: res.apiResource,
          table: res.table,
          deleted,
          failed,
          total: ids.length,
        });
      }

      const moduleReport: ResetModuleReport = {
        moduleId: modId,
        moduleLabel: mod.label,
        resources: resourceReports,
        totalDeleted: resourceReports.reduce((s, r) => s + r.deleted, 0),
        totalFailed: resourceReports.reduce((s, r) => s + r.failed, 0),
      };

      moduleReports.push(moduleReport);
      callbacks.onModuleComplete?.(modId, moduleReport);
    }

    // --- Phase 3: build final report ---
    const finishedAt = new Date().toISOString();

    progress.status = this.cancelled ? 'cancelled' : 'completed';
    progress.currentModuleId = null;
    progress.currentResource = null;
    callbacks.onProgress?.(cloneProgress(progress));

    return {
      modules: moduleReports,
      totalDeleted: moduleReports.reduce((s, m) => s + m.totalDeleted, 0),
      totalFailed: moduleReports.reduce((s, m) => s + m.totalFailed, 0),
      errors: allErrors,
      startedAt,
      finishedAt,
      status: this.cancelled ? 'cancelled' : 'completed',
    };
  }
}

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

function cloneProgress(p: ResetProgress): ResetProgress {
  return {
    ...p,
    resourceProgress: p.resourceProgress.map((r) => ({ ...r })),
  };
}

const parser = new XMLParser({
  ignoreAttributes: false,
  attributeNamePrefix: '@_',
  parseTagValue: true,
});

function extractErrorMessage(error: unknown): string {
  if (axios.isAxiosError(error)) {
    const data = error.response?.data;
    if (typeof data === 'string') {
      try {
        const parsed = parser.parse(data);
        const apiError = parsed?.prestashop?.errors?.error;
        if (Array.isArray(apiError)) {
          return apiError.map((e: any) => e.message || e['#text']).filter(Boolean).join('; ');
        }
        if (apiError?.message) return apiError.message;
      } catch {
        // fall through
      }
    }
    return error.message || 'API delete error';
  }

  if (error instanceof Error) return error.message;
  return 'Unknown error';
}
