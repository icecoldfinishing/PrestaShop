/**
 * Types for the modular data reset system.
 *
 * These types define the structure of reset modules, API resources,
 * progress tracking, and reporting for the PrestaShop data reset feature.
 */

// ---------------------------------------------------------------------------
// Module configuration types
// ---------------------------------------------------------------------------

/** A single API resource that can be deleted via the PrestaShop XML API. */
export interface ResetApiResource {
  /** PrestaShop API resource name (e.g. 'customers', 'orders'). */
  apiResource: string;

  /** Corresponding database table (e.g. 'ps_customer'). */
  table: string;

  /** Singular XML tag used in API responses (e.g. 'customer'). */
  singularTag: string;
}

/** A database table cleaned automatically via cascade (no direct API endpoint). */
export interface CascadeTable {
  /** Database table name (e.g. 'ps_connections'). */
  table: string;

  /** Which parent resource triggers the cascade cleanup. */
  cascadeParent: string;
}

/** Full configuration for a single reset module. */
export interface ResetModuleConfig {
  /** Unique module identifier (e.g. 'customers', 'orders', 'products'). */
  id: string;

  /** Human-readable label displayed in the UI. */
  label: string;

  /** Short description of what this module resets. */
  description: string;

  /** Bootstrap Icon class name (e.g. 'bi-people'). */
  icon: string;

  /** Whether this module is checked by default in the UI. */
  defaultChecked: boolean;

  /**
   * Ordered list of API resources to delete.
   * The order matters: dependents (children) must be listed BEFORE parents
   * to avoid foreign key constraint violations.
   */
  apiResources: ResetApiResource[];

  /**
   * Tables that are automatically cleaned when a parent resource is deleted.
   * These do NOT have their own API endpoint.
   */
  cascadeTables: CascadeTable[];

  /**
   * IDs of other modules that must be reset BEFORE this one.
   * Used for topological sort of module execution order.
   */
  dependsOn: string[];
}

/** Map of module ID → module configuration. */
export type ResetModuleConfigMap = Record<string, ResetModuleConfig>;

// ---------------------------------------------------------------------------
// Runtime state types
// ---------------------------------------------------------------------------

/** Represents the checked/unchecked state of a module in the UI. */
export interface ResetModuleState {
  /** Module ID. */
  id: string;

  /** Module configuration reference. */
  config: ResetModuleConfig;

  /** Whether the user has selected this module for reset. */
  checked: boolean;
}

/** Progress tracking for a single API resource during reset. */
export interface ResetResourceProgress {
  /** API resource name. */
  apiResource: string;

  /** Total items to delete. */
  total: number;

  /** Items processed so far. */
  processed: number;

  /** Successfully deleted items. */
  success: number;

  /** Failed deletions. */
  failed: number;
}

/** Overall progress tracking during a reset operation. */
export interface ResetProgress {
  /** Current status of the reset operation. */
  status: 'idle' | 'counting' | 'running' | 'paused' | 'cancelled' | 'completed';

  /** Currently processing module ID (null if idle/completed). */
  currentModuleId: string | null;

  /** Currently processing resource name (null if idle/completed). */
  currentResource: string | null;

  /** Percentage complete (0–100). */
  percent: number;

  /** Total items across all selected modules. */
  totalItems: number;

  /** Total items processed across all modules. */
  processedItems: number;

  /** Per-resource progress details. */
  resourceProgress: ResetResourceProgress[];
}

// ---------------------------------------------------------------------------
// Report types
// ---------------------------------------------------------------------------

/** Error entry in the reset report. */
export interface ResetError {
  /** Module ID where the error occurred. */
  moduleId: string;

  /** API resource name. */
  apiResource: string;

  /** ID of the item that failed to delete. */
  itemId: string | number;

  /** Error message from the API. */
  message: string;

  /** Optional detailed error information. */
  details?: string;
}

/** Report for a single resource within a module. */
export interface ResetResourceReport {
  /** API resource name. */
  apiResource: string;

  /** Database table name. */
  table: string;

  /** Number of items successfully deleted. */
  deleted: number;

  /** Number of items that failed to delete. */
  failed: number;

  /** Total items that were targeted for deletion. */
  total: number;
}

/** Report for a single module. */
export interface ResetModuleReport {
  /** Module ID. */
  moduleId: string;

  /** Module label. */
  moduleLabel: string;

  /** Per-resource reports. */
  resources: ResetResourceReport[];

  /** Total items deleted in this module. */
  totalDeleted: number;

  /** Total items that failed in this module. */
  totalFailed: number;
}

/** Final report for the entire reset operation. */
export interface ResetReport {
  /** Per-module reports. */
  modules: ResetModuleReport[];

  /** Grand total of items deleted. */
  totalDeleted: number;

  /** Grand total of items that failed. */
  totalFailed: number;

  /** All errors encountered. */
  errors: ResetError[];

  /** ISO timestamp when the reset started. */
  startedAt: string;

  /** ISO timestamp when the reset finished. */
  finishedAt: string;

  /** Final status. */
  status: 'completed' | 'cancelled';
}
