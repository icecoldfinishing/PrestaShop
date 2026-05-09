export type FieldType = 'string' | 'number' | 'integer' | 'boolean' | 'date';

export interface CsvParseOptions {
  delimiter?: string;
  hasHeader?: boolean;
  encoding?: string;
}

export interface ImportSettings {
  csvDelimiter: string;
  listSeparator: string;
  dateFormat: string;
  decimalSeparator: string;
  thousandSeparator: string;
  encoding: string;
  hasHeader: boolean;
  batchSize: number;
  mapping: Record<string, string>;
  requiredFields: string[];
  fieldTypes: Record<string, FieldType>;
  defaultValues: Record<string, string>;
}

export interface ResourceConfig {
  label: string;
  resourceName: string;
  xmlRootTag: string;
  xmlItemTag: string;
  apiEndpoint: string;
  httpMethod: 'POST' | 'PUT';
  mapping: Record<string, string>;
  requiredFields?: string[];
  fieldTypes?: Record<string, FieldType>;
  listSeparator?: string;
  batchSize?: number;
}

export type ResourceConfigMap = Record<string, ResourceConfig>;

export type CsvRow = Record<string, string>;

export interface CsvParseResult {
  headers: string[];
  rows: CsvRow[];
  rowCount: number;
}

export interface ImportRow {
  id: string;
  data: CsvRow;
  selected: boolean;
  errors: string[];
  status: 'pending' | 'success' | 'error' | 'skipped';
  originalIndex: number;
}

export interface ImportProgress {
  total: number;
  processed: number;
  success: number;
  failed: number;
  percent: number;
  status: 'idle' | 'running' | 'paused' | 'cancelled' | 'completed';
}

export interface ImportError {
  rowId: string;
  rowNumber: number;
  message: string;
  details?: string;
}

export interface ImportSuccess {
  rowId: string;
  rowNumber: number;
  responseId?: string;
}

export interface ImportReport {
  resourceKey: string;
  resourceName: string;
  total: number;
  success: number;
  failed: number;
  errors: ImportError[];
  successes: ImportSuccess[];
  startedAt: string;
  finishedAt: string;
  status: 'completed' | 'cancelled';
}

export interface ImportFile {
  id: string;
  file: File;
  name: string;
  size: number;
  status: 'ready' | 'parsing' | 'parsed' | 'error';
  parseResult?: CsvParseResult;
  rows: ImportRow[];
  resourceKey?: string;
  errors: string[];
  settings: ImportSettings;
}
