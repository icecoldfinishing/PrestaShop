import type { CsvParseOptions, CsvParseResult, CsvRow } from '../types/import.types';

export class CSVReaderService {
  async parseFile(file: File, options: CsvParseOptions = {}): Promise<CsvParseResult> {
    const text = await readFileAsText(file, options.encoding);
    return parseCSVText(text, options);
  }
}

export function parseCSVText(text: string, options: CsvParseOptions = {}): CsvParseResult {
  const cleaned = stripBom(text);
  if (!cleaned.trim()) {
    return { headers: [], rows: [], rowCount: 0 };
  }

  const delimiter = resolveDelimiter(cleaned, options.delimiter);
  const matrix = parseToMatrix(cleaned, delimiter);

  if (matrix.length === 0) {
    return { headers: [], rows: [], rowCount: 0 };
  }

  const hasHeader = options.hasHeader !== false;
  const headerRow = hasHeader ? matrix[0] : buildHeaderRow(matrix);
  const rawHeaders = headerRow.map((header) => normalizeHeader(header));
  const headers = ensureUniqueHeaders(rawHeaders);
  const rows: CsvRow[] = [];

  const startIndex = hasHeader ? 1 : 0;
  for (let i = startIndex; i < matrix.length; i += 1) {
    const rowArr = matrix[i];
    if (rowArr.length === 1 && rowArr[0].trim() === '') {
      continue;
    }

    const row: CsvRow = {};
    headers.forEach((header, index) => {
      row[header] = normalizeCellValue(rowArr[index] ?? '');
    });

    rows.push(row);
  }

  return {
    headers,
    rows,
    rowCount: rows.length
  };
}

function stripBom(text: string): string {
  return text.replace(/^\uFEFF/, '');
}

function normalizeHeader(value: string): string {
  return stripBom(String(value)).trim();
}

function ensureUniqueHeaders(headers: string[]): string[] {
  const seen = new Map<string, number>();
  return headers.map((header, index) => {
    const base = header || `Column ${index + 1}`;
    const count = seen.get(base) ?? 0;
    seen.set(base, count + 1);
    return count === 0 ? base : `${base} (${count + 1})`;
  });
}

function normalizeCellValue(value: string): string {
  const trimmed = String(value ?? '').trim();
  if (!trimmed) {
    return '';
  }
  return trimmed;
}

function resolveDelimiter(text: string, delimiter?: string): string {
  if (delimiter && delimiter !== 'auto') {
    return delimiter;
  }

  if (!delimiter) {
    return ',';
  }

  return detectDelimiter(text);
}

function detectDelimiter(text: string): string {
  const delimiters = [',', ';', '\t', '|'];
  const sampleLines = text.split(/\r?\n/).slice(0, 10);

  let best = { delimiter: ',', score: -1 };

  for (const delimiter of delimiters) {
    let total = 0;
    for (const line of sampleLines) {
      total += countDelimiterOutsideQuotes(line, delimiter);
    }

    if (total > best.score) {
      best = { delimiter, score: total };
    }
  }

  return best.delimiter;
}

function countDelimiterOutsideQuotes(line: string, delimiter: string): number {
  let count = 0;
  let inQuotes = false;

  for (let i = 0; i < line.length; i += 1) {
    const char = line[i];
    const next = line[i + 1];

    if (char === '"') {
      if (inQuotes && next === '"') {
        i += 1;
        continue;
      }
      inQuotes = !inQuotes;
      continue;
    }

    if (char === delimiter && !inQuotes) {
      count += 1;
    }
  }

  return count;
}

function parseToMatrix(text: string, delimiter: string): string[][] {
  const rows: string[][] = [];
  let row: string[] = [];
  let field = '';
  let inQuotes = false;

  for (let i = 0; i < text.length; i += 1) {
    const char = text[i];
    const next = text[i + 1];

    if (inQuotes) {
      if (char === '"') {
        if (next === '"') {
          field += '"';
          i += 1;
        } else {
          inQuotes = false;
        }
      } else {
        field += char;
      }
      continue;
    }

    if (char === '"') {
      inQuotes = true;
      continue;
    }

    if (char === delimiter) {
      row.push(field);
      field = '';
      continue;
    }

    if (char === '\n') {
      row.push(field);
      rows.push(row);
      row = [];
      field = '';
      continue;
    }

    if (char === '\r') {
      continue;
    }

    field += char;
  }

  row.push(field);
  rows.push(row);

  return rows;
}

function buildHeaderRow(matrix: string[][]): string[] {
  const columnCount = matrix.reduce((max, row) => Math.max(max, row.length), 0);
  return Array.from({ length: columnCount }, (_, index) => `Column ${index + 1}`);
}

function readFileAsText(file: File, encoding?: string): Promise<string> {
  if (typeof FileReader === 'undefined') {
    return file.text();
  }

  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onload = () => resolve(String(reader.result || ''));
    reader.onerror = () => reject(reader.error || new Error('File read error'));
    reader.readAsText(file, encoding || 'utf-8');
  });
}
