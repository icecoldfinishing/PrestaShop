<script setup lang="ts">
import type { ImportReport } from '../../types/import.types';

const props = defineProps<{
    report: ImportReport;
}>();

const emit = defineEmits<{
    (e: 'export-json'): void;
    (e: 'export-csv'): void;
    (e: 'retry-errors'): void;
}>();
</script>

<template>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <h5 class="mb-1">Import report</h5>
                    <div class="text-muted small">
                        Total: {{ report.total }} | Success: {{ report.success }} | Errors: {{ report.failed }}
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary" @click="emit('export-json')">Export JSON</button>
                    <button class="btn btn-outline-secondary" @click="emit('export-csv')">Export CSV</button>
                    <button class="btn btn-outline-primary" @click="emit('retry-errors')">Retry errors</button>
                </div>
            </div>

            <div v-if="report.errors.length" class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Row</th>
                            <th>Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="error in report.errors.slice(0, 20)" :key="error.rowId">
                            <td>{{ error.rowNumber }}</td>
                            <td>{{ error.message }}</td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="report.errors.length > 20" class="text-muted small">
                    Showing first 20 errors.
                </div>
            </div>

            <div v-else class="text-success">No errors reported.</div>
        </div>
    </div>
</template>
