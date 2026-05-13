import axios from 'axios';
import { XMLParser } from 'fast-xml-parser';
import type { ImportProgress, ImportReport, ImportRow, ResourceConfig } from '../types/import.types';
import { buildXML } from './XMLBuilder.service';

const API_KEY = import.meta.env.VITE_PRESTASHOP_API_KEY;
const BASE_URL = import.meta.env.VITE_PRESTASHOP_BASE_URL || '/api';

const parser = new XMLParser({
  ignoreAttributes: false,
  attributeNamePrefix: '@_',
  parseTagValue: true
});

export type ImportCallbacks = {
  onRowSuccess?: (row: ImportRow, responseId?: string) => void;
  onRowError?: (row: ImportRow, message: string, details?: string) => void;
  onProgress?: (progress: ImportProgress) => void;
};

export class ImportBatchService {
  private paused = false;
  private cancelled = false;
  private resumePromise: Promise<void> | null = null;
  private resumeResolver: (() => void) | null = null;

  pause(): void {
    if (this.paused) {
      return;
    }
    this.paused = true;
    this.resumePromise = new Promise((resolve) => {
      this.resumeResolver = resolve;
    });
  }

  resume(): void {
    if (!this.paused) {
      return;
    }
    this.paused = false;
    if (this.resumeResolver) {
      this.resumeResolver();
    }
    this.resumeResolver = null;
    this.resumePromise = null;
  }

  cancel(): void {
    this.cancelled = true;
    this.resume();
  }

  async run(rows: ImportRow[], config: ResourceConfig, callbacks: ImportCallbacks = {}): Promise<ImportReport> {
    const total = rows.length;
    let processed = 0;
    let success = 0;
    let failed = 0;

    const errors: ImportReport['errors'] = [];
    const successes: ImportReport['successes'] = [];

    const startedAt = new Date().toISOString();

    const size = config.batchSize || 20;
    for (let offset = 0; offset < rows.length; offset += size) {
      const batch = rows.slice(offset, offset + size);

      for (const row of batch) {
        if (this.cancelled) {
          break;
        }

        if (this.paused && this.resumePromise) {
          await this.resumePromise;
        }

        try {
          const responseId = await sendRow(row, config);
          success += 1;
          successes.push({
            rowId: row.id,
            rowNumber: row.originalIndex,
            responseId
          });
          callbacks.onRowSuccess?.(row, responseId);
        } catch (error) {
          failed += 1;
          const { message, details } = normalizeError(error);
          errors.push({
            rowId: row.id,
            rowNumber: row.originalIndex,
            message,
            details
          });
          callbacks.onRowError?.(row, message, details);
        }

        processed += 1;
        callbacks.onProgress?.(buildProgress(total, processed, success, failed, this.cancelled));
      }
    }

    const finishedAt = new Date().toISOString();

    return {
      resourceKey: config.resourceName,
      resourceName: config.resourceName,
      total,
      success,
      failed,
      errors,
      successes,
      startedAt,
      finishedAt,
      status: this.cancelled ? 'cancelled' : 'completed'
    };
  }
}

function buildProgress(
  total: number,
  processed: number,
  success: number,
  failed: number,
  cancelled: boolean
): ImportProgress {
  const percent = total === 0 ? 0 : Math.round((processed / total) * 100);
  return {
    total,
    processed,
    success,
    failed,
    percent,
    status: cancelled ? 'cancelled' : 'running'
  };
}

async function sendRow(row: ImportRow, config: ResourceConfig): Promise<string | undefined> {
  const xml = buildXML([row.data], config);
  const endpoint = resolveEndpoint(config);

  const headers: Record<string, string> = {
    'Content-Type': 'application/xml'
  };

  const authHeader = buildAuthHeader();
  if (authHeader) {
    headers.Authorization = authHeader;
  }

  const method = config.httpMethod.toLowerCase();

  const response = await axios.request({
    method,
    url: endpoint,
    data: xml,
    headers,
    params: {
      ws_key: API_KEY
    }
  });

  const responseId = extractResponseId(response.data, config);
  return responseId;
}

function resolveEndpoint(config: ResourceConfig): string {
  if (config.apiEndpoint.startsWith('http')) {
    return config.apiEndpoint;
  }

  if (config.apiEndpoint.startsWith('/')) {
    return config.apiEndpoint;
  }

  return `${BASE_URL.replace(/\/$/, '')}/${config.apiEndpoint}`;
}

function buildAuthHeader(): string | undefined {
  if (!API_KEY || typeof btoa !== 'function') {
    return undefined;
  }

  const token = `${API_KEY}:`;
  const encoded = btoa(token);
  return `Basic ${encoded}`;
}

function extractResponseId(xmlText: string, config: ResourceConfig): string | undefined {
  if (!xmlText) {
    return undefined;
  }

  try {
    const data = parser.parse(xmlText);
    const item = data?.prestashop?.[config.xmlItemTag];
    return item?.['@_id'] || item?.id;
  } catch {
    return undefined;
  }
}

function normalizeError(error: unknown): { message: string; details?: string } {
  if (axios.isAxiosError(error)) {
    const data = error.response?.data;
    const parsed = parsePrestaShopError(data);
    return {
      message: parsed.message || error.message || 'Import error',
      details: parsed.details
    };
  }

  if (error instanceof Error) {
    return { message: error.message };
  }

  return { message: 'Import error' };
}

function parsePrestaShopError(xmlText: string | undefined): { message?: string; details?: string } {
  if (!xmlText || typeof xmlText !== 'string') {
    return {};
  }

  try {
    const data = parser.parse(xmlText);
    const error = data?.prestashop?.errors?.error;

    if (Array.isArray(error)) {
      const messages = error
        .map((item) => item.message || item['#text'])
        .filter(Boolean)
        .join('; ');
      return { message: messages };
    }

    if (error?.message) {
      return { message: error.message };
    }

    if (error && typeof error === 'string') {
      return { message: error };
    }

    return {};
  } catch {
    return {};
  }
}
