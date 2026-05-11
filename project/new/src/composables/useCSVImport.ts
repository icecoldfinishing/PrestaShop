import { computed, ref } from 'vue';
import { RESOURCE_CONFIGS } from '../config/resources.mapping';
import { RESET_MODULES, RESET_MODULE_IDS } from '../config/reset-modules.config';
import type {
    CsvParseOptions,
    CsvParseResult,
    FieldType,
    ImportFile,
    ImportProgress,
    ImportReport,
    ImportRow,
    ImportSettings,
    ResourceConfig
} from '../types/import.types';
import { CSVReaderService } from '../services/CSVReader.service';
import { ImportBatchService } from '../services/ImportBatch.service';

const reader = new CSVReaderService();

/**
 * Maps reset module IDs to their importable resource keys.
 * This links the reset module config to the import resource config.
 */
const MODULE_RESOURCE_MAP: Record<string, string[]> = {
    customers: ['customers'],
    orders: ['orders'],
    products: ['products', 'categories', 'brands', 'suppliers'],
};

const DEFAULT_SETTINGS: ImportSettings = {
    csvDelimiter: ';',
    listSeparator: ';',
    dateFormat: 'dd/mm/yyyy',
    decimalSeparator: ',',
    thousandSeparator: ' ',
    encoding: 'utf-8',
    hasHeader: true,
    batchSize: 20,
    mapping: {},
    requiredFields: [],
    fieldTypes: {},
    defaultValues: {}
};

export function useCSVImport() {
    const files = ref<ImportFile[]>([]);
    const selectedFileId = ref<string | null>(null);
    const baseSettings = ref<ImportSettings>(createBaseSettings());
    const progress = ref<ImportProgress>({
        total: 0,
        processed: 0,
        success: 0,
        failed: 0,
        percent: 0,
        status: 'idle'
    });
    const report = ref<ImportReport | null>(null);
    const activeService = ref<ImportBatchService | null>(null);

    /**
     * Selected module IDs for module-aware import filtering.
     * All modules are selected by default.
     */
    const selectedImportModules = ref<string[]>(
        RESET_MODULE_IDS.filter((id) => RESET_MODULES[id]?.defaultChecked)
    );

    const selectedFile = computed(() => files.value.find((file) => file.id === selectedFileId.value) || null);

    /** All available resource options (unfiltered). */
    const resourceOptions = computed(() =>
        Object.entries(RESOURCE_CONFIGS).map(([key, config]) => ({
            key,
            label: config.label
        }))
    );

    /**
     * Resource options filtered by the currently selected modules.
     * When modules are selected, only resources belonging to those modules are shown.
     */
    const moduleFilteredResourceOptions = computed(() => {
        if (selectedImportModules.value.length === 0) {
            return resourceOptions.value;
        }

        const allowedKeys = new Set<string>();
        for (const modId of selectedImportModules.value) {
            const keys = MODULE_RESOURCE_MAP[modId] || [];
            keys.forEach((k) => allowedKeys.add(k));
        }

        return resourceOptions.value.filter((opt) => allowedKeys.has(opt.key));
    });

    /** Toggle a module for import filtering. */
    const toggleImportModule = (moduleId: string) => {
        const idx = selectedImportModules.value.indexOf(moduleId);
        if (idx >= 0) {
            selectedImportModules.value.splice(idx, 1);
        } else {
            selectedImportModules.value.push(moduleId);
        }
    };

    const addFiles = async (fileList: FileList | File[]) => {
        const incoming = Array.from(fileList);
        for (const file of incoming) {
            await addSingleFile(file);
        }
    };

    const addSingleFile = async (file: File) => {
        const fileId = generateId();
        const guessedResourceKey = guessResourceKey(file.name);
        const guessedConfig = guessedResourceKey ? RESOURCE_CONFIGS[guessedResourceKey] : undefined;
        const settings = buildFileSettings(baseSettings.value);
        const entry: ImportFile = {
            id: fileId,
            file,
            name: file.name,
            size: file.size,
            status: 'parsing',
            rows: [],
            errors: [],
            settings
        };

        if (guessedResourceKey) {
            entry.resourceKey = guessedResourceKey;
        }

        files.value.push(entry);

        try {
            const parseResult = await reader.parseFile(file, buildParseOptions(settings));
            entry.parseResult = parseResult;
            entry.status = 'parsed';

            syncSettingsWithHeaders(entry, guessedConfig, true);
            buildRowsForFile(entry, parseResult);
            validateFile(entry);

            if (!selectedFileId.value) {
                selectedFileId.value = entry.id;
            }
        } catch (error) {
            entry.status = 'error';
            entry.errors.push('CSV parse failed');
        }
    };

    const removeFile = (fileId: string) => {
        files.value = files.value.filter((file) => file.id !== fileId);
        if (selectedFileId.value === fileId) {
            selectedFileId.value = files.value[0]?.id || null;
        }
    };

    const selectFile = (fileId: string) => {
        selectedFileId.value = fileId;
    };

    const setFileResource = (fileId: string, resourceKey: string) => {
        const file = files.value.find((item) => item.id === fileId);
        if (!file) {
            return;
        }

        file.resourceKey = resourceKey;
        const config = RESOURCE_CONFIGS[resourceKey];
        syncSettingsWithHeaders(file, config, true);
        validateFile(file);
        file.rows.forEach((row) => {
            row.status = 'pending';
        });
    };

    const toggleAllRows = (fileId: string, selected: boolean) => {
        const file = files.value.find((item) => item.id === fileId);
        if (!file) {
            return;
        }

        file.rows.forEach((row) => {
            row.selected = selected;
        });
    };

    const toggleRow = (fileId: string, rowId: string, selected: boolean) => {
        const file = files.value.find((item) => item.id === fileId);
        if (!file) {
            return;
        }

        const row = file.rows.find((item) => item.id === rowId);
        if (row) {
            row.selected = selected;
        }
    };

    const updateCell = (fileId: string, rowId: string, header: string, value: string) => {
        const file = files.value.find((item) => item.id === fileId);
        if (!file) {
            return;
        }

        const row = file.rows.find((item) => item.id === rowId);
        if (!row) {
            return;
        }

        row.data[header] = value;
        row.errors = validateRow(row, file.settings);
    };

    const removeRow = (fileId: string, rowId: string) => {
        const file = files.value.find((item) => item.id === fileId);
        if (!file) {
            return;
        }
        file.rows = file.rows.filter((row) => row.id !== rowId);
    };

    const updateSettings = async (changes: Partial<ImportSettings>) => {
        const previous = baseSettings.value;
        const next: ImportSettings = {
            ...previous,
            ...changes,
            mapping: {},
            requiredFields: [],
            fieldTypes: {},
            defaultValues: {}
        };

        baseSettings.value = next;

        const parsingChanged =
            previous.csvDelimiter !== next.csvDelimiter ||
            previous.hasHeader !== next.hasHeader ||
            previous.encoding !== next.encoding;

        for (const file of files.value) {
            file.settings = applyBaseSettings(file.settings, next);
            const config = file.resourceKey ? RESOURCE_CONFIGS[file.resourceKey] : undefined;

            if (parsingChanged) {
                await reparseFile(file, config);
                continue;
            }

            syncSettingsWithHeaders(file, config);
            validateFile(file);
        }
    };

    const updateMapping = (fileId: string, header: string, path: string) => {
        const file = files.value.find((item) => item.id === fileId);
        if (!file) {
            return;
        }
        file.settings.mapping = { ...file.settings.mapping, [header]: path };
        validateFile(file);
    };

    const updateDefaultValue = (fileId: string, header: string, value: string) => {
        const file = files.value.find((item) => item.id === fileId);
        if (!file) {
            return;
        }
        file.settings.defaultValues = { ...file.settings.defaultValues, [header]: value };
        validateFile(file);
    };

    const updateFieldType = (fileId: string, header: string, fieldType: FieldType | '') => {
        const file = files.value.find((item) => item.id === fileId);
        if (!file) {
            return;
        }

        const next = { ...file.settings.fieldTypes } as Record<string, FieldType>;
        if (!fieldType) {
            delete next[header];
        } else {
            next[header] = fieldType;
        }
        file.settings.fieldTypes = next;
        validateFile(file);
    };

    const toggleRequiredField = (fileId: string, header: string, required: boolean) => {
        const file = files.value.find((item) => item.id === fileId);
        if (!file) {
            return;
        }

        const requiredSet = new Set(file.settings.requiredFields);
        if (required) {
            requiredSet.add(header);
        } else {
            requiredSet.delete(header);
        }
        file.settings.requiredFields = Array.from(requiredSet);
        validateFile(file);
    };

    const startImport = async (fileId?: string, rowsOverride?: ImportRow[]) => {
        const file = files.value.find((item) => item.id === (fileId || selectedFileId.value));
        if (!file || !file.resourceKey) {
            return;
        }

        const config = buildResourceConfig(file);
        if (!config) {
            return;
        }

        if (Object.keys(config.mapping).length === 0) {
            file.errors = ['Missing column mapping'];
            return;
        }

        const rows = rowsOverride || file.rows;
        const rowsToImport = rows.filter((row) => row.selected && row.errors.length === 0);
        rowsToImport.forEach((row) => {
            row.status = 'pending';
        });

        report.value = null;
        progress.value = {
            total: rowsToImport.length,
            processed: 0,
            success: 0,
            failed: 0,
            percent: 0,
            status: 'running'
        };

        const service = new ImportBatchService();
        activeService.value = service;

        const reportResult = await service.run(rowsToImport, config, {
            onRowSuccess: (row) => {
                row.status = 'success';
                row.errors = [];
            },
            onRowError: (row, message) => {
                row.status = 'error';
                row.errors = [message];
            },
            onProgress: (p) => {
                progress.value = { ...p, status: progress.value.status };
            }
        });

        report.value = reportResult;
        progress.value.status = reportResult.status === 'cancelled' ? 'cancelled' : 'completed';
        activeService.value = null;
    };

    const pauseImport = () => {
        if (!activeService.value) {
            return;
        }
        activeService.value.pause();
        progress.value.status = 'paused';
    };

    const resumeImport = () => {
        if (!activeService.value) {
            return;
        }
        activeService.value.resume();
        progress.value.status = 'running';
    };

    const cancelImport = () => {
        if (!activeService.value) {
            return;
        }
        activeService.value.cancel();
        progress.value.status = 'cancelled';
    };

    const retryErrors = async (fileId?: string) => {
        const file = files.value.find((item) => item.id === (fileId || selectedFileId.value));
        if (!file) {
            return;
        }

        const errorRows = file.rows.filter((row) => row.status === 'error');
        errorRows.forEach((row) => {
            row.selected = true;
            row.status = 'pending';
        });

        await startImport(file.id, errorRows);
    };

    const exportReportJson = () => {
        if (!report.value) {
            return;
        }
        const content = JSON.stringify(report.value, null, 2);
        downloadBlob(content, `import-report-${report.value.resourceName}.json`, 'application/json');
    };

    const exportReportCsv = () => {
        if (!report.value) {
            return;
        }

        const lines = ['rowNumber,status,message'];

        report.value.successes.forEach((item) => {
            lines.push(`${item.rowNumber},success,ok`);
        });

        report.value.errors.forEach((item) => {
            lines.push(`${item.rowNumber},error,"${escapeCsv(item.message)}"`);
        });

        downloadBlob(lines.join('\n'), `import-report-${report.value.resourceName}.csv`, 'text/csv');
    };

    return {
        files,
        selectedFile,
        selectedFileId,
        baseSettings,
        progress,
        report,
        resourceOptions,
        moduleFilteredResourceOptions,
        selectedImportModules,
        addFiles,
        removeFile,
        selectFile,
        setFileResource,
        toggleAllRows,
        toggleRow,
        updateCell,
        removeRow,
        updateSettings,
        startImport,
        pauseImport,
        resumeImport,
        cancelImport,
        retryErrors,
        exportReportJson,
        exportReportCsv,
        toggleImportModule
    };
}

function buildRowsForFile(file: ImportFile, parseResult: CsvParseResult): void {
    const rowOffset = file.settings.hasHeader ? 2 : 1;
    file.rows = parseResult.rows.map((row, index) => ({
        id: generateId(),
        data: row,
        selected: true,
        errors: [],
        status: 'pending',
        originalIndex: index + rowOffset
    }));
}

function validateFile(file: ImportFile): void {
    file.errors = [];

    if (!file.parseResult) {
        return;
    }

    const headers = file.parseResult.headers;
    const required = file.settings.requiredFields || [];

    required.forEach((field) => {
        if (!headers.includes(field)) {
            file.errors.push(`Missing required column: ${field}`);
        }
    });

    file.rows.forEach((row) => {
        row.errors = validateRow(row, file.settings);
    });
}

function validateRow(row: ImportRow, settings: ImportSettings): string[] {
    if (!settings) {
        return [];
    }

    const errors: string[] = [];
    const defaults = settings.defaultValues || {};
    Object.entries(defaults).forEach(([field, defaultValue]) => {
        const current = row.data[field];
        if (!current || String(current).trim() === '') {
            row.data[field] = defaultValue;
        }
    });

    const required = settings.requiredFields || [];
    required.forEach((field) => {
        const value = row.data[field];
        if (!value || String(value).trim() === '') {
            errors.push(`Missing value for ${field}`);
        }
    });

    const fieldTypes = settings.fieldTypes || {};
    Object.entries(fieldTypes).forEach(([field, type]) => {
        const rawValue = row.data[field];
        if (!rawValue) {
            return;
        }

        const { valid, normalized } = validateType(rawValue, type, settings);
        if (!valid) {
            errors.push(`Invalid ${type} for ${field}`);
            return;
        }

        if (normalized !== null) {
            row.data[field] = normalized;
        }
    });

    return errors;
}

function validateType(value: string, type: FieldType, settings: ImportSettings): { valid: boolean; normalized: string | null } {
    const trimmed = String(value).trim();

    if (type === 'number') {
        const normalized = normalizeNumber(trimmed, settings, false);
        return { valid: normalized !== null, normalized };
    }

    if (type === 'integer') {
        const normalized = normalizeNumber(trimmed, settings, true);
        return { valid: normalized !== null, normalized };
    }

    if (type === 'boolean') {
        const normalized = normalizeBoolean(trimmed);
        return { valid: normalized !== null, normalized: normalized ?? null };
    }

    if (type === 'date') {
        const normalized = normalizeDate(trimmed, settings);
        return { valid: normalized !== null, normalized };
    }

    return { valid: true, normalized: trimmed };
}

function normalizeBoolean(value: string): string | null {
    const lower = value.toLowerCase();
    if (['1', 'true', 'yes', 'y'].includes(lower)) {
        return '1';
    }
    if (['0', 'false', 'no', 'n'].includes(lower)) {
        return '0';
    }
    return null;
}

function normalizeNumber(value: string, settings: ImportSettings, integerOnly: boolean): string | null {
    let normalized = value.trim();
    const decimal = settings.decimalSeparator || '.';
    const thousand = settings.thousandSeparator || '';

    if (thousand && thousand !== decimal) {
        normalized = normalized.split(thousand).join('');
    }

    if (decimal && decimal !== '.') {
        normalized = normalized.split(decimal).join('.');
    }

    const num = Number(normalized);
    if (Number.isNaN(num)) {
        return null;
    }

    if (integerOnly && !Number.isInteger(num)) {
        return null;
    }

    return integerOnly ? String(Math.trunc(num)) : normalized;
}

function normalizeDate(value: string, settings: ImportSettings): string | null {
    if (/^\d{4}-\d{2}-\d{2}/.test(value)) {
        return value;
    }

    const numeric = Number(value);
    if (!Number.isNaN(numeric) && numeric >= 20000 && numeric <= 80000) {
        const epoch = new Date(Date.UTC(1899, 11, 30));
        const millis = epoch.getTime() + numeric * 24 * 60 * 60 * 1000;
        return new Date(millis).toISOString().slice(0, 10);
    }

    const formatted = parseDateWithFormat(value, settings.dateFormat);
    if (formatted) {
        return formatted;
    }

    const parsed = Date.parse(value);
    if (!Number.isNaN(parsed)) {
        return new Date(parsed).toISOString().slice(0, 10);
    }

    return null;
}

function parseDateWithFormat(value: string, format: string): string | null {
    const trimmed = value.trim();
    if (!trimmed) {
        return null;
    }

    const tokens = ['yyyy', 'mm', 'dd'];
    let regex = '^';
    const order: string[] = [];
    let cursor = 0;

    while (cursor < format.length) {
        const token = tokens.find((item) => format.startsWith(item, cursor));
        if (token) {
            if (token === 'yyyy') {
                regex += '(\\d{4})';
            } else {
                regex += '(\\d{1,2})';
            }
            order.push(token);
            cursor += token.length;
        } else {
            regex += escapeRegex(format[cursor]);
            cursor += 1;
        }
    }

    regex += '$';
    const match = new RegExp(regex).exec(trimmed);
    if (!match) {
        return null;
    }

    const parts: Record<string, number> = {};
    order.forEach((token, index) => {
        parts[token] = Number(match[index + 1]);
    });

    const year = parts.yyyy;
    const month = parts.mm;
    const day = parts.dd;

    if (!year || !month || !day) {
        return null;
    }

    const date = new Date(Date.UTC(year, month - 1, day));
    if (
        date.getUTCFullYear() !== year ||
        date.getUTCMonth() !== month - 1 ||
        date.getUTCDate() !== day
    ) {
        return null;
    }

    return date.toISOString().slice(0, 10);
}

function escapeRegex(value: string): string {
    return value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

function createBaseSettings(): ImportSettings {
    return {
        ...DEFAULT_SETTINGS,
        mapping: {},
        requiredFields: [],
        fieldTypes: {},
        defaultValues: {}
    };
}

function buildFileSettings(base: ImportSettings): ImportSettings {
    return {
        ...base,
        mapping: {},
        requiredFields: [],
        fieldTypes: {},
        defaultValues: {}
    };
}

function applyBaseSettings(current: ImportSettings, base: ImportSettings): ImportSettings {
    return {
        ...current,
        ...base,
        mapping: current.mapping,
        requiredFields: current.requiredFields,
        fieldTypes: current.fieldTypes,
        defaultValues: current.defaultValues
    };
}

function buildParseOptions(settings: ImportSettings): CsvParseOptions {
    return {
        delimiter: settings.csvDelimiter,
        hasHeader: settings.hasHeader,
        encoding: settings.encoding
    };
}

async function reparseFile(file: ImportFile, config?: ResourceConfig): Promise<void> {
    file.status = 'parsing';
    file.errors = [];

    try {
        const parseResult = await reader.parseFile(file.file, buildParseOptions(file.settings));
        file.parseResult = parseResult;
        file.status = 'parsed';

        buildRowsForFile(file, parseResult);
        syncSettingsWithHeaders(file, config);
        validateFile(file);
    } catch (error) {
        file.status = 'error';
        file.errors.push('CSV parse failed');
    }
}

function syncSettingsWithHeaders(file: ImportFile, config?: ResourceConfig, forceDefaults = false): void {
    if (!file.parseResult) {
        return;
    }

    const headers = file.parseResult.headers;
    const nextMapping: Record<string, string> = {};

    headers.forEach((header) => {
        if (!forceDefaults && header in file.settings.mapping) {
            nextMapping[header] = file.settings.mapping[header];
            return;
        }

        nextMapping[header] = config?.mapping?.[header] || '';
    });

    file.settings.mapping = nextMapping;

    const requiredBase = forceDefaults
        ? config?.requiredFields || []
        : file.settings.requiredFields.length
          ? file.settings.requiredFields
          : config?.requiredFields || [];
    file.settings.requiredFields = headers.filter((header) => requiredBase.includes(header));

    const nextTypes: Record<string, FieldType> = {};
    headers.forEach((header) => {
        if (!forceDefaults && file.settings.fieldTypes[header]) {
            nextTypes[header] = file.settings.fieldTypes[header];
            return;
        }

        const configType = config?.fieldTypes?.[header];
        if (configType) {
            nextTypes[header] = configType;
        }
    });
    file.settings.fieldTypes = nextTypes;

    const nextDefaults: Record<string, string> = forceDefaults
        ? {}
        : { ...file.settings.defaultValues };

    headers.forEach((header) => {
        if (file.settings.defaultValues[header] !== undefined) {
            nextDefaults[header] = file.settings.defaultValues[header];
            return;
        }

        if (config?.defaultValues?.[header] !== undefined) {
            nextDefaults[header] = config.defaultValues[header] as string;
        }
    });

    if (config?.defaultValues) {
        Object.entries(config.defaultValues).forEach(([key, value]) => {
            if (nextDefaults[key] === undefined) {
                nextDefaults[key] = value as string;
            }
        });
    }

    file.settings.defaultValues = nextDefaults;

}

function buildResourceConfig(file: ImportFile): ResourceConfig | null {
    if (!file.resourceKey) {
        return null;
    }

    const base = RESOURCE_CONFIGS[file.resourceKey];
    const mappingEntries = Object.entries(file.settings.mapping).filter(([, path]) => path && path.trim() !== '');
    const mapping = Object.fromEntries(mappingEntries);

    const defaults = file.settings.defaultValues || {};
    Object.keys(defaults).forEach((key) => {
        if (!mapping[key] && base.mapping?.[key]) {
            mapping[key] = base.mapping[key];
        }
    });

    return {
        ...base,
        mapping,
        requiredFields: file.settings.requiredFields,
        fieldTypes: file.settings.fieldTypes,
        listSeparator: file.settings.listSeparator,
        batchSize: file.settings.batchSize
    };
}

function guessResourceKey(filename: string): string | undefined {
    const lower = filename.toLowerCase();
    if (lower.includes('product')) return 'products';
    if (lower.includes('customer') || lower.includes('client')) return 'customers';
    if (lower.includes('order') || lower.includes('commande')) return 'orders';
    if (lower.includes('categorie') || lower.includes('category')) return 'categories';
    if (lower.includes('brand') || lower.includes('manufacturer')) return 'brands';
    if (lower.includes('supplier') || lower.includes('fournisseur')) return 'suppliers';
    return undefined;
}

function generateId(): string {
    if (typeof crypto !== 'undefined' && 'randomUUID' in crypto) {
        return crypto.randomUUID();
    }
    return `${Date.now().toString(36)}-${Math.random().toString(36).slice(2)}`;
}

function downloadBlob(content: string, filename: string, type: string): void {
    const blob = new Blob([content], { type });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
}

function escapeCsv(value: string): string {
    return String(value).replace(/"/g, '""');
}
