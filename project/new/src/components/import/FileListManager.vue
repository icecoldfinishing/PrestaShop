<script setup lang="ts">
import { computed } from 'vue';
import type { ImportFile } from '../../types/import.types';

const props = defineProps<{
    files: ImportFile[];
    resourceOptions: Array<{ key: string; label: string }>;
    activeFileId: string | null;
}>();

const emit = defineEmits<{
    (e: 'preview', fileId: string): void;
    (e: 'remove', fileId: string): void;
    (e: 'resource-change', fileId: string, resourceKey: string): void;
    (e: 'start-import', fileId: string): void;
}>();

const hasFiles = computed(() => props.files.length > 0);

const formatBytes = (bytes: number) => {
    if (bytes < 1024) return `${bytes} B`;
    const kb = bytes / 1024;
    if (kb < 1024) return `${kb.toFixed(1)} KB`;
    return `${(kb / 1024).toFixed(1)} MB`;
};
</script>

<template>
    <div>
        <div v-if="!hasFiles" class="text-muted">No CSV files yet</div>

        <div v-else class="list-group">
            <div v-for="file in files" :key="file.id" class="list-group-item list-group-item-action"
                :class="{ active: file.id === activeFileId }">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <div>
                        <div class="fw-semibold">{{ file.name }}</div>
                        <small class="text-muted">{{ formatBytes(file.size) }}</small>
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <select class="form-select form-select-sm" style="width: 180px;" :value="file.resourceKey || ''"
                            @change="emit('resource-change', file.id, ($event.target as HTMLSelectElement).value)">
                            <option value="" disabled>Select resource</option>
                            <option v-for="option in resourceOptions" :key="option.key" :value="option.key">
                                {{ option.label }}
                            </option>
                        </select>

                        <button class="btn btn-sm btn-outline-primary" type="button" @click="emit('preview', file.id)">
                            Preview
                        </button>
                        <button class="btn btn-sm btn-success" type="button" @click="emit('start-import', file.id)">
                            Import
                        </button>
                        <button class="btn btn-sm btn-outline-danger" type="button" @click="emit('remove', file.id)">
                            Delete
                        </button>
                    </div>
                </div>

                <div v-if="file.errors.length" class="text-danger small mt-2">
                    <div v-for="(error, index) in file.errors" :key="index">{{ error }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
