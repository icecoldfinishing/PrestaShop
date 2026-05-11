import { computed, ref } from 'vue';
import axios from 'axios';
import { XMLBuilder } from 'fast-xml-parser';
import { RESOURCE_CONFIGS } from '../config/resources.mapping';
import { RESET_MODULES, RESET_MODULE_IDS } from '../config/reset-modules.config';
import type {
    CsvParseOptions,
    CsvParseResult,
    CsvRow,
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
import { buildXML } from '../services/XMLBuilder.service';
import { psDelete, psGet, psPost, psPut } from '../utils/prestashop-api';

const reader = new CSVReaderService();
const API_KEY = (import.meta as any)?.env?.VITE_PRESTASHOP_API_KEY as string | undefined;
const xmlBuilder = new XMLBuilder({
    ignoreAttributes: false,
    attributeNamePrefix: '@_',
    suppressEmptyNode: true
});

const DEFAULT_IDS = {
    language: 1,
    country: 1,
    currency: 1,
    shop: 1,
    shopGroup: 1
};

/**
 * Maps reset module IDs to their importable resource keys.
 * This links the reset module config to the import resource config.
 */
const MODULE_RESOURCE_MAP: Record<string, string[]> = {
    customers: ['customers'],
    orders: ['orders'],
    products: ['products', 'variants', 'categories', 'brands', 'suppliers'],
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
    const imageFiles = ref<File[]>([]);
    const imageIndex = ref<Record<string, File[]>>({});

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

    const addImages = (incoming: FileList | File[]) => {
        const filesToAdd = Array.from(incoming);
        if (filesToAdd.length === 0) {
            return;
        }

        imageFiles.value = [...imageFiles.value, ...filesToAdd];
        imageIndex.value = buildImageIndex(imageFiles.value);
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

    const startImportAll = async () => {
        if (files.value.length === 0) {
            return;
        }

        report.value = null;

        const orderedFiles = orderFilesForImport(files.value);
        const allRows = orderedFiles.flatMap((file) =>
            file.rows.filter((row) => row.selected && row.errors.length === 0)
        );

        progress.value = {
            total: allRows.length,
            processed: 0,
            success: 0,
            failed: 0,
            percent: 0,
            status: 'running'
        };

        const successes: ImportReport['successes'] = [];
        const errors: ImportReport['errors'] = [];
        const createdItems: Array<{ resource: string; id: string }> = [];

        const updateProgress = (success: boolean) => {
            progress.value.processed += 1;
            if (success) {
                progress.value.success += 1;
            } else {
                progress.value.failed += 1;
            }
            progress.value.percent =
                progress.value.total === 0
                    ? 0
                    : Math.round((progress.value.processed / progress.value.total) * 100);
        };

        const runtimeCaches = {
            categoryByName: new Map<string, string>(),
            taxGroupByRate: new Map<number, string>(),
            taxGroupCreated: new Set<string>(),
            productByReference: new Map<string, string>(),
            attributeGroupByName: new Map<string, string>(),
            attributeValueByKey: new Map<string, string>()
        };

        for (const file of orderedFiles) {
            if (!file.resourceKey) {
                continue;
            }

            const config = buildResourceConfig(file);
            if (!config) {
                continue;
            }

            const rowsToImport = file.rows.filter((row) => row.selected && row.errors.length === 0);
            if (rowsToImport.length === 0) {
                continue;
            }

            try {
                if (file.resourceKey === 'products') {
                    await importProducts(
                        rowsToImport,
                        config,
                        runtimeCaches,
                        createdItems,
                        successes,
                        imageIndex.value,
                        updateProgress
                    );
                } else if (file.resourceKey === 'variants') {
                    await importVariants(
                        rowsToImport,
                        runtimeCaches,
                        createdItems,
                        successes,
                        updateProgress
                    );
                } else {
                    await importGeneric(rowsToImport, config, createdItems, successes, updateProgress);
                }
            } catch (error) {
                const { message } = normalizeError(error);
                errors.push({
                    rowId: 'all',
                    rowNumber: 0,
                    message: message || 'Import failed'
                });
                break;
            }
        }

        if (errors.length > 0) {
            await rollbackCreatedItems(createdItems);
        }

        report.value = {
            resourceKey: 'all',
            resourceName: 'all',
            total: progress.value.total,
            success: progress.value.success,
            failed: progress.value.failed,
            errors,
            successes,
            startedAt: new Date().toISOString(),
            finishedAt: new Date().toISOString(),
            status: errors.length > 0 ? 'cancelled' : 'completed'
        };

        progress.value.status = errors.length > 0 ? 'cancelled' : 'completed';
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
        addImages,
        removeFile,
        selectFile,
        setFileResource,
        toggleAllRows,
        toggleRow,
        updateCell,
        removeRow,
        updateSettings,
        startImport,
        startImportAll,
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

function buildImageIndex(files: File[]): Record<string, File[]> {
    const index: Record<string, File[]> = {};
    files.forEach((file) => {
        const key = normalizeReference(file.name);
        if (!key) {
            return;
        }
        if (!index[key]) {
            index[key] = [];
        }
        index[key].push(file);
    });
    return index;
}

function normalizeReference(value: string): string {
    const trimmed = value.trim();
    if (!trimmed) {
        return '';
    }
    const base = trimmed.replace(/\.[^.]+$/, '');
    return base.toLowerCase();
}

function orderFilesForImport(items: ImportFile[]): ImportFile[] {
    const order = ['products', 'variants', 'customers', 'orders'];
    return [...items].sort((a, b) => {
        const aIndex = order.indexOf(a.resourceKey || '');
        const bIndex = order.indexOf(b.resourceKey || '');
        if (aIndex === -1 && bIndex === -1) return 0;
        if (aIndex === -1) return 1;
        if (bIndex === -1) return -1;
        return aIndex - bIndex;
    });
}

async function importGeneric(
    rows: ImportRow[],
    config: ResourceConfig,
    createdItems: Array<{ resource: string; id: string }>,
    successes: ImportReport['successes'],
    updateProgress: (success: boolean) => void
): Promise<void> {
    for (const row of rows) {
        const responseId = await sendRow(row.data, config);
        if (responseId) {
            createdItems.push({ resource: config.resourceName, id: String(responseId) });
            successes.push({ rowId: row.id, rowNumber: row.originalIndex, responseId: String(responseId) });
        }
        updateProgress(true);
    }
}

async function importProducts(
    rows: ImportRow[],
    config: ResourceConfig,
    caches: {
        categoryByName: Map<string, string>;
        taxGroupByRate: Map<number, string>;
        taxGroupCreated: Set<string>;
        productByReference: Map<string, string>;
        attributeGroupByName: Map<string, string>;
        attributeValueByKey: Map<string, string>;
    },
    createdItems: Array<{ resource: string; id: string }>,
    successes: ImportReport['successes'],
    imageIndex: Record<string, File[]>,
    updateProgress: (success: boolean) => void
): Promise<void> {
    for (const row of rows) {
        await hydrateProductRow(row.data, caches, createdItems);
        const responseId = await sendRow(row.data, config);
        if (responseId) {
            const id = String(responseId);
            const refKey = normalizeReference(row.data.reference || '');
            if (refKey) {
                caches.productByReference.set(refKey, id);
            }

            createdItems.push({ resource: config.resourceName, id });
            successes.push({ rowId: row.id, rowNumber: row.originalIndex, responseId: id });

            const images = imageIndex[normalizeReference(row.data.reference || '')] || [];
            if (images.length > 0) {
                await uploadProductImages(id, images);
            }
        }

        updateProgress(true);
    }
}

async function importVariants(
    rows: ImportRow[],
    caches: {
        categoryByName: Map<string, string>;
        taxGroupByRate: Map<number, string>;
        taxGroupCreated: Set<string>;
        productByReference: Map<string, string>;
        attributeGroupByName: Map<string, string>;
        attributeValueByKey: Map<string, string>;
    },
    createdItems: Array<{ resource: string; id: string }>,
    successes: ImportReport['successes'],
    updateProgress: (success: boolean) => void
): Promise<void> {
    for (const row of rows) {
        const reference = String(row.data.reference || '').trim();
        if (!reference) {
            throw new Error('Variant row missing reference');
        }

        const productId = await resolveProductId(reference, caches);
        const attributeGroupName = String(row.data.specificité || '').trim();
        const attributeValueName = String(row.data.karazany || '').trim();
        const stockValue = row.data.stock_initial ? Number(row.data.stock_initial) : 0;
        const priceTtc = row.data.prix_vente_ttc ? String(row.data.prix_vente_ttc) : '';

        if (!attributeGroupName || !attributeValueName) {
            await updateStock(productId, 0, stockValue);
            updateProgress(true);
            continue;
        }

        const groupId = await ensureAttributeGroup(attributeGroupName, caches, createdItems);
        const valueId = await ensureAttributeValue(groupId, attributeValueName, caches, createdItems);
        const combinationId = await createCombination(productId, valueId, reference, priceTtc);
        if (combinationId) {
            createdItems.push({ resource: 'combinations', id: combinationId });
            successes.push({ rowId: row.id, rowNumber: row.originalIndex, responseId: combinationId });
            await updateStock(productId, Number(combinationId), stockValue);
        }

        updateProgress(true);
    }
}

async function hydrateProductRow(
    row: CsvRow,
    caches: {
        categoryByName: Map<string, string>;
        taxGroupByRate: Map<number, string>;
        taxGroupCreated: Set<string>;
    },
    createdItems: Array<{ resource: string; id: string }>
): Promise<void> {
    const rawCategory = String(row.categorie || '').trim();
    if (rawCategory) {
        const categoryId = await ensureCategory(rawCategory, caches, DEFAULT_IDS.language, createdItems);
        row.categorie = categoryId;
    }

    const rawTax = String(row.Taxe || '').trim();
    const taxRate = rawTax ? parseTaxRate(rawTax) : null;
    if (taxRate !== null) {
        const taxGroupId = await ensureTaxRulesGroup(taxRate, caches, DEFAULT_IDS.language, createdItems);
        row.Taxe = taxGroupId;
    }

    const rawPriceTtc = row.prix_ttc ? String(row.prix_ttc) : '';
    if (rawPriceTtc) {
        const priceHt = computePriceHt(rawPriceTtc, taxRate ?? 0);
        if (priceHt !== null) {
            row.prix_ttc = priceHt;
        }
    }
}

function parseTaxRate(value: string): number | null {
    const cleaned = value.replace('%', '').replace(',', '.').trim();
    if (!cleaned) {
        return null;
    }
    const rate = Number(cleaned);
    return Number.isNaN(rate) ? null : rate;
}

function computePriceHt(value: string, rate: number): string | null {
    const cleaned = value.replace(',', '.').trim();
    const price = Number(cleaned);
    if (Number.isNaN(price)) {
        return null;
    }
    if (!rate) {
        return String(price);
    }
    const ht = price / (1 + rate / 100);
    return ht.toFixed(2);
}

async function ensureCategory(
    name: string,
    caches: { categoryByName: Map<string, string> },
    languageId: number,
    createdItems: Array<{ resource: string; id: string }>
): Promise<string> {
    const key = name.toLowerCase();
    const cached = caches.categoryByName.get(key);
    if (cached) {
        return cached;
    }

    const data = (await psGet('categories', '', {
        'filter[name]': `[${name}]`,
        display: '[id]'
    })) as any;

    const category = data?.prestashop?.categories?.category;
    const found = Array.isArray(category) ? category[0] : category;
    const existingId = found?.['@_id'] || found?.id;
    if (existingId) {
        const id = String(existingId);
        caches.categoryByName.set(key, id);
        return id;
    }

    const payload = {
        name: {
            language: {
                '@_id': languageId,
                '#text': name
            }
        },
        link_rewrite: {
            language: {
                '@_id': languageId,
                '#text': slugify(name)
            }
        },
        id_parent: '2',
        active: '1'
    };

    const xml = buildResourceXml('category', payload);
    const response = await psPost('categories', xml);
    const id = extractResourceId(response, 'category');
    if (!id) {
        throw new Error(`Failed to create category: ${name}`);
    }
    caches.categoryByName.set(key, id);
    createdItems.push({ resource: 'categories', id });
    return id;
}

async function ensureTaxRulesGroup(
    rate: number,
    caches: { taxGroupByRate: Map<number, string>; taxGroupCreated: Set<string> },
    languageId: number,
    createdItems: Array<{ resource: string; id: string }>
): Promise<string> {
    const cached = caches.taxGroupByRate.get(rate);
    if (cached) {
        return cached;
    }

    const label = `Import ${rate}%`;
    const data = (await psGet('tax_rules_groups', '', {
        'filter[name]': `[${label}]`,
        display: '[id]'
    })) as any;

    const group = data?.prestashop?.tax_rules_groups?.tax_rules_group;
    const found = Array.isArray(group) ? group[0] : group;
    const existingId = found?.['@_id'] || found?.id;
    if (existingId) {
        const id = String(existingId);
        caches.taxGroupByRate.set(rate, id);
        return id;
    }

    const groupXml = buildResourceXml('tax_rules_group', {
        name: label,
        active: '1'
    });
    const groupResponse = await psPost('tax_rules_groups', groupXml);
    const groupId = extractResourceId(groupResponse, 'tax_rules_group');
    if (!groupId) {
        throw new Error(`Failed to create tax rules group for ${rate}%`);
    }
    createdItems.push({ resource: 'tax_rules_groups', id: groupId });

    const taxXml = buildResourceXml('tax', {
        name: {
            language: {
                '@_id': languageId,
                '#text': `Tax ${rate}%`
            }
        },
        rate: String(rate),
        active: '1'
    });
    const taxResponse = await psPost('taxes', taxXml);
    const taxId = extractResourceId(taxResponse, 'tax');
    if (taxId) {
        createdItems.push({ resource: 'taxes', id: taxId });
        const ruleXml = buildResourceXml('tax_rule', {
            id_tax_rules_group: groupId,
            id_country: String(DEFAULT_IDS.country),
            id_state: '0',
            id_tax: taxId,
            behavior: '0',
            description: label
        });
        const ruleResponse = await psPost('tax_rules', ruleXml);
        const ruleId = extractResourceId(ruleResponse, 'tax_rule');
        if (ruleId) {
            createdItems.push({ resource: 'tax_rules', id: ruleId });
        }
    }

    caches.taxGroupByRate.set(rate, groupId);
    caches.taxGroupCreated.add(groupId);
    return groupId;
}

async function resolveProductId(reference: string, caches: { productByReference: Map<string, string> }): Promise<string> {
    const key = normalizeReference(reference);
    const cached = caches.productByReference.get(key);
    if (cached) {
        return cached;
    }

    const data = (await psGet('products', '', {
        'filter[reference]': `[${reference}]`,
        display: '[id]'
    })) as any;
    const product = data?.prestashop?.products?.product;
    const found = Array.isArray(product) ? product[0] : product;
    const id = found?.['@_id'] || found?.id;
    if (!id) {
        throw new Error(`Product not found for reference ${reference}`);
    }

    const productId = String(id);
    caches.productByReference.set(key, productId);
    return productId;
}

async function ensureAttributeGroup(
    name: string,
    caches: { attributeGroupByName: Map<string, string> },
    createdItems: Array<{ resource: string; id: string }>
): Promise<string> {
    const key = name.toLowerCase();
    const cached = caches.attributeGroupByName.get(key);
    if (cached) {
        return cached;
    }

    const data = (await psGet('product_options', '', {
        'filter[name]': `[${name}]`,
        display: '[id]'
    })) as any;
    const group = data?.prestashop?.product_options?.product_option;
    const found = Array.isArray(group) ? group[0] : group;
    const existingId = found?.['@_id'] || found?.id;
    if (existingId) {
        const id = String(existingId);
        caches.attributeGroupByName.set(key, id);
        return id;
    }

    const xml = buildResourceXml('product_option', {
        group_type: 'select',
        is_color_group: '0',
        name: {
            language: { '@_id': DEFAULT_IDS.language, '#text': name }
        },
        public_name: {
            language: { '@_id': DEFAULT_IDS.language, '#text': name }
        }
    });
    const response = await psPost('product_options', xml);
    const id = extractResourceId(response, 'product_option');
    if (!id) {
        throw new Error(`Failed to create attribute group: ${name}`);
    }
    caches.attributeGroupByName.set(key, id);
    createdItems.push({ resource: 'product_options', id });
    return id;
}

async function ensureAttributeValue(
    groupId: string,
    value: string,
    caches: { attributeValueByKey: Map<string, string> },
    createdItems: Array<{ resource: string; id: string }>
): Promise<string> {
    const key = `${groupId}::${value.toLowerCase()}`;
    const cached = caches.attributeValueByKey.get(key);
    if (cached) {
        return cached;
    }

    const data = (await psGet('product_option_values', '', {
        'filter[id_attribute_group]': `[${groupId}]`,
        'filter[name]': `[${value}]`,
        display: '[id]'
    })) as any;
    const entry = data?.prestashop?.product_option_values?.product_option_value;
    const found = Array.isArray(entry) ? entry[0] : entry;
    const existingId = found?.['@_id'] || found?.id;
    if (existingId) {
        const id = String(existingId);
        caches.attributeValueByKey.set(key, id);
        return id;
    }

    const xml = buildResourceXml('product_option_value', {
        id_attribute_group: groupId,
        name: {
            language: { '@_id': DEFAULT_IDS.language, '#text': value }
        }
    });
    const response = await psPost('product_option_values', xml);
    const id = extractResourceId(response, 'product_option_value');
    if (!id) {
        throw new Error(`Failed to create attribute value: ${value}`);
    }
    caches.attributeValueByKey.set(key, id);
    createdItems.push({ resource: 'product_option_values', id });
    return id;
}

async function createCombination(
    productId: string,
    valueId: string,
    reference: string,
    priceTtc: string
): Promise<string | undefined> {
    let priceImpact = '0';
    if (priceTtc) {
        const price = computePriceHt(priceTtc, 0);
        if (price !== null) {
            priceImpact = price;
        }
    }

    const xml = buildResourceXml('combination', {
        id_product: productId,
        reference,
        minimal_quantity: '1',
        price: priceImpact,
        associations: {
            product_option_values: {
                product_option_value: {
                    id: valueId
                }
            }
        }
    });

    const response = await psPost('combinations', xml);
    const id = extractResourceId(response, 'combination');
    return id || undefined;
}

async function updateStock(productId: string, productAttributeId: number, quantity: number): Promise<void> {
    const data = (await psGet('stock_availables', '', {
        'filter[id_product]': `[${productId}]`,
        'filter[id_product_attribute]': `[${productAttributeId}]`,
        display: '[id]'
    })) as any;

    const item = data?.prestashop?.stock_availables?.stock_available;
    const found = Array.isArray(item) ? item[0] : item;
    const stockId = found?.['@_id'] || found?.id;
    if (!stockId) {
        return;
    }

    const xml = buildResourceXml('stock_available', {
        id: String(stockId),
        id_product: String(productId),
        id_product_attribute: String(productAttributeId),
        quantity: String(quantity)
    });
    await psPut(`stock_availables/${stockId}`, xml);
}

async function uploadProductImages(productId: string, files: File[]): Promise<void> {
    for (const file of files) {
        const form = new FormData();
        form.append('image', file);

        await axios.post(`/api/images/products/${productId}`, form, {
            params: {
                ws_key: API_KEY
            },
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    }
}

async function rollbackCreatedItems(items: Array<{ resource: string; id: string }>): Promise<void> {
    const reversed = [...items].reverse();
    for (const item of reversed) {
        try {
            await psDelete(item.resource, item.id);
        } catch {
            // ignore rollback errors
        }
    }
}

function buildResourceXml(tag: string, payload: Record<string, unknown>): string {
    return xmlBuilder.build({
        prestashop: {
            [tag]: payload
        }
    });
}

function extractResourceId(xml: string, tag: string): string | null {
    if (!xml) {
        return null;
    }

    try {
        const parser = new DOMParser();
        const doc = parser.parseFromString(xml, 'application/xml');
        const node = doc.querySelector(`${tag}[id]`);
        const id = node?.getAttribute('id');
        return id || null;
    } catch {
        return null;
    }
}

function slugify(value: string): string {
    return value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)+/g, '')
        .slice(0, 64) || 'imported';
}

async function sendRow(row: CsvRow, config: ResourceConfig): Promise<string | undefined> {
    const xml = buildXML([row], config);

    const response = await axios.request({
        method: config.httpMethod.toLowerCase(),
        url: resolveEndpoint(config),
        data: xml,
        headers: {
            'Content-Type': 'application/xml'
        },
        params: {
            ws_key: API_KEY
        }
    });

    return extractResponseId(response.data, config);
}

function resolveEndpoint(config: ResourceConfig): string {
    if (config.apiEndpoint.startsWith('http')) {
        return config.apiEndpoint;
    }

    if (config.apiEndpoint.startsWith('/')) {
        return config.apiEndpoint;
    }

    return `/api/${config.apiEndpoint}`;
}

function extractResponseId(xmlText: string, config: ResourceConfig): string | undefined {
    if (!xmlText) {
        return undefined;
    }

    try {
        const parser = new DOMParser();
        const doc = parser.parseFromString(xmlText, 'application/xml');
        const node = doc.querySelector(`${config.xmlItemTag}[id]`);
        return node?.getAttribute('id') || undefined;
    } catch {
        return undefined;
    }
}

function normalizeError(error: unknown): { message: string } {
    if (axios.isAxiosError(error)) {
        return { message: error.message || 'Import error' };
    }

    if (error instanceof Error) {
        return { message: error.message };
    }

    return { message: 'Import error' };
}


function guessResourceKey(filename: string): string | undefined {
    const lower = filename.toLowerCase();
    if (lower.includes('product')) return 'products';
    if (lower.includes('variant') || lower.includes('combination') || lower.includes('fichier2')) return 'variants';
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
