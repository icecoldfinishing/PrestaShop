<script setup lang="ts">
import { computed, ref } from 'vue';
import type { ImportRow } from '../../types/import.types';

const props = defineProps<{
    headers: string[];
    rows: ImportRow[];
    fileId: string;
    maxRows?: number;
}>();

const emit = defineEmits<{
    (e: 'toggle-all', fileId: string, selected: boolean): void;
    (e: 'toggle-row', fileId: string, rowId: string, selected: boolean): void;
    (e: 'update-cell', fileId: string, rowId: string, header: string, value: string): void;
    (e: 'remove-row', fileId: string, rowId: string): void;
}>();

const maxRows = computed(() => props.maxRows ?? 20);
const visibleRows = computed(() => props.rows.slice(0, maxRows.value));
const allSelected = computed(() => props.rows.length > 0 && props.rows.every((row) => row.selected));

const editingCell = ref<{ rowId: string; header: string } | null>(null);
const editValue = ref('');

const startEdit = (rowId: string, header: string, value: string) => {
    editingCell.value = { rowId, header };
    editValue.value = value;
};

const saveEdit = () => {
    if (!editingCell.value) {
        return;
    }
    emit('update-cell', props.fileId, editingCell.value.rowId, editingCell.value.header, editValue.value);
    editingCell.value = null;
};

const isEditing = (rowId: string, header: string) =>
    editingCell.value?.rowId === rowId && editingCell.value?.header === header;
</script>

<template>
    <div class="table-responsive">
        <table class="table table-sm table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 48px;">
                        <input type="checkbox" class="form-check-input" :checked="allSelected"
                            @change="emit('toggle-all', fileId, ($event.target as HTMLInputElement).checked)" />
                    </th>
                    <th v-for="header in headers" :key="header">{{ header }}</th>
                    <th style="width: 90px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in visibleRows" :key="row.id" :class="{ 'table-danger': row.errors.length > 0 }">
                    <td>
                        <input type="checkbox" class="form-check-input" :checked="row.selected"
                            @change="emit('toggle-row', fileId, row.id, ($event.target as HTMLInputElement).checked)" />
                    </td>
                    <td v-for="header in headers" :key="header"
                        @dblclick="startEdit(row.id, header, row.data[header] || '')">
                        <div v-if="isEditing(row.id, header)">
                            <input v-model="editValue" class="form-control form-control-sm" type="text" @blur="saveEdit"
                                @keyup.enter="saveEdit" />
                        </div>
                        <div v-else class="text-truncate" style="max-width: 240px;">
                            {{ row.data[header] }}
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" @click="emit('remove-row', fileId, row.id)">
                            Remove
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>X
    </div>
</template>
