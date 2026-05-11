<script setup lang="ts">
import { computed } from 'vue';
import type { ImportSettings } from '../../types/import.types';

const props = defineProps<{
    settings: ImportSettings;
}>();

const emit = defineEmits<{
    (e: 'update-settings', changes: Partial<ImportSettings>): void;
}>();

const delimiterValue = computed(() =>
    props.settings.csvDelimiter === '\t' ? 'tab' : props.settings.csvDelimiter
);

const setSetting = (key: keyof ImportSettings, value: string | number | boolean) => {
    emit('update-settings', { [key]: value } as Partial<ImportSettings>);
};

const normalizeDelimiter = (value: string) => {
    if (value === 'tab') {
        return '\t';
    }
    return value;
};
</script>

<template>
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <h5 class="mb-1">Parametres d'import (globaux)</h5>
                    <div class="text-muted small">
                        Appliques a tous les fichiers. Le mapping est defini dans le code.
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-3">
                    <label class="form-label">Separateur CSV</label>
                    <select class="form-select form-select-sm" :value="delimiterValue"
                        @change="setSetting('csvDelimiter', normalizeDelimiter(($event.target as HTMLSelectElement).value))">
                        <option value=",">Comma (,)</option>
                        <option value=";">Semicolon (;)</option>
                        <option value="tab">Tab</option>
                        <option value="|">Pipe (|)</option>
                        <option value="auto">Auto detect</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">Separateur listes</label>
                    <select class="form-select form-select-sm" :value="settings.listSeparator"
                        @change="setSetting('listSeparator', ($event.target as HTMLSelectElement).value)">
                        <option value=",">Comma (,)</option>
                        <option value=";">Semicolon (;)</option>
                        <option value="|">Pipe (|)</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">Format date</label>
                    <select class="form-select form-select-sm" :value="settings.dateFormat"
                        @change="setSetting('dateFormat', ($event.target as HTMLSelectElement).value)">
                        <option value="yyyy-mm-dd">yyyy-mm-dd</option>
                        <option value="dd/mm/yyyy">dd/mm/yyyy</option>
                        <option value="mm/dd/yyyy">mm/dd/yyyy</option>
                        <option value="dd-mm-yyyy">dd-mm-yyyy</option>
                        <option value="yyyy/mm/dd">yyyy/mm/dd</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">Encodage</label>
                    <select class="form-select form-select-sm" :value="settings.encoding"
                        @change="setSetting('encoding', ($event.target as HTMLSelectElement).value)">
                        <option value="utf-8">UTF-8</option>
                        <option value="iso-8859-1">ISO-8859-1</option>
                        <option value="windows-1252">Windows-1252</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">Separateur decimal</label>
                    <select class="form-select form-select-sm" :value="settings.decimalSeparator"
                        @change="setSetting('decimalSeparator', ($event.target as HTMLSelectElement).value)">
                        <option value=".">Dot (.)</option>
                        <option value=",">Comma (,)</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">Separateur milliers</label>
                    <select class="form-select form-select-sm" :value="settings.thousandSeparator"
                        @change="setSetting('thousandSeparator', ($event.target as HTMLSelectElement).value)">
                        <option value=",">Comma (,)</option>
                        <option value=".">Dot (.)</option>
                        <option value=" ">Space</option>
                        <option value="">None</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label">Batch size</label>
                    <input class="form-control form-control-sm" type="number" min="1" :value="settings.batchSize"
                        @input="setSetting('batchSize', Number(($event.target as HTMLInputElement).value))" />
                </div>
                <div class="col-12 col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" :checked="settings.hasHeader"
                            @change="setSetting('hasHeader', ($event.target as HTMLInputElement).checked)" />
                        <label class="form-check-label">Premiere ligne en-tete</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
