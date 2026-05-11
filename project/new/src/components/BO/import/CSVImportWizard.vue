<script setup lang="ts">
import { computed } from 'vue';
import UploadZone from './UploadZone.vue';
import FileListManager from './FileListManager.vue';
import PreviewTable from './PreviewTable.vue';
import ImportProgress from './ImportProgress.vue';
import ImportReport from './ImportReport.vue';
import ImportSettingsPanel from './ImportSettingsPanel.vue';
import { useCSVImport } from '../../../composables/useCSVImport';

const {
    files,
    selectedFile,
    selectedFileId,
    baseSettings,
    progress,
    report,
    resourceOptions,
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
    exportReportCsv
} = useCSVImport();

const selectedSummary = computed(() => {
    if (!selectedFile.value) {
        return { total: 0, selected: 0, errors: 0 };
    }

    const total = selectedFile.value.rows.length;
    const selected = selectedFile.value.rows.filter((row) => row.selected).length;
    const errors = selectedFile.value.rows.filter((row) => row.errors.length > 0).length;
    return { total, selected, errors };
});

const canImport = computed(() => {
    if (!selectedFile.value) {
        return false;
    }
    if (!selectedFile.value.resourceKey) {
        return false;
    }
    const hasMapping = Object.values(selectedFile.value.settings.mapping || {}).some(
        (path) => typeof path === 'string' && path.trim() !== ''
    );
    return selectedSummary.value.selected > 0 && hasMapping;
});

const canImportAll = computed(() => {
    return files.value.length > 0 && files.value.every(f => f.resourceKey && Object.keys(f.settings.mapping || {}).length > 0);
});
</script>

<template>
    <div class="container-fluid">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
            <div>
                <h2 class="fw-bold mb-1">CSV Import</h2>
                <p class="text-muted mb-0">Import CSV files and push them to PrestaShop API</p>
            </div>
            <div>
                <button class="btn btn-warning fw-bold" :disabled="!canImportAll" @click="startImportAll">
                    <i class="bi bi-cloud-arrow-up"></i>
                    Tout Importer (All or Nothing)
                </button>
            </div>
        </div>

        <UploadZone @files-added="addFiles" @images-added="addImages" />

        <FileListManager class="mt-3" :files="files" :resource-options="resourceOptions"
            :active-file-id="selectedFileId" @preview="selectFile" @remove="removeFile"
            @resource-change="setFileResource" @start-import="startImport" />

        <ImportSettingsPanel class="mt-4" :settings="baseSettings" @update-settings="updateSettings" />

        <div v-if="selectedFile" class="mt-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                <div>
                    <h5 class="mb-0">Preview</h5>
                    <small class="text-muted">
                        Rows: {{ selectedSummary.total }} | Selected: {{ selectedSummary.selected }} | Errors: {{
                        selectedSummary.errors }}
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success" :disabled="!canImport" @click="startImport(selectedFile.id)">
                        <i class="bi bi-cloud-upload"></i>
                        Import selected
                    </button>
                    <button class="btn btn-outline-secondary" :disabled="!selectedFile.resourceKey"
                        @click="retryErrors(selectedFile.id)">
                        <i class="bi bi-arrow-repeat"></i>
                        Retry errors
                    </button>
                </div>
            </div>

            <PreviewTable :headers="selectedFile.parseResult?.headers || []" :rows="selectedFile.rows"
                :file-id="selectedFile.id" @toggle-all="toggleAllRows" @toggle-row="toggleRow" @update-cell="updateCell"
                @remove-row="removeRow" />
        </div>

        <ImportProgress v-if="progress.status !== 'idle'" class="mt-4" :progress="progress" @pause="pauseImport"
            @resume="resumeImport" @cancel="cancelImport" />

        <ImportReport v-if="report" class="mt-4" :report="report" @export-json="exportReportJson"
            @export-csv="exportReportCsv" @retry-errors="retryErrors(selectedFile?.id)" />
    </div>
</template>
