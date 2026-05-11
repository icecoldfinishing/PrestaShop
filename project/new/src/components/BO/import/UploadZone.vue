<script setup lang="ts">
import { ref } from 'vue';

const emit = defineEmits<{
    (e: 'files-added', files: File[]): void;
    (e: 'images-added', files: File[]): void;
}>();

const inputRef = ref<HTMLInputElement | null>(null);
const imageInputRef = ref<HTMLInputElement | null>(null);
const isDragging = ref(false);

const openFileDialog = () => {
    inputRef.value?.click();
};

const openImageDialog = () => {
    imageInputRef.value?.click();
};

const handleFiles = (fileList: FileList | null) => {
    if (!fileList || fileList.length === 0) {
        return;
    }
    emit('files-added', Array.from(fileList));
};

const handleImageFiles = (fileList: FileList | null) => {
    if (!fileList || fileList.length === 0) {
        return;
    }
    emit('images-added', Array.from(fileList));
};

const onInputChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    handleFiles(target.files);
    if (target.value) {
        target.value = '';
    }
};

const onImageInputChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    handleImageFiles(target.files);
    if (target.value) {
        target.value = '';
    }
};

const onDrop = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = false;
    handleFiles(event.dataTransfer?.files || null);
};

const onDragOver = (event: DragEvent) => {
    event.preventDefault();
    isDragging.value = true;
};

const onDragLeave = () => {
    isDragging.value = false;
};
</script>

<template>
    <div class="upload-zone p-4 text-center rounded border" :class="{ 'bg-light': isDragging }" @drop="onDrop"
        @dragover="onDragOver" @dragleave="onDragLeave">
        <input ref="inputRef" type="file" class="d-none" accept=".csv,text/csv" multiple @change="onInputChange" />
        <input ref="imageInputRef" type="file" class="d-none" accept="image/*" webkitdirectory directory multiple @change="onImageInputChange" />

        <div class="d-flex justify-content-center gap-2 mb-2">
            <button class="btn btn-primary" type="button" @click="openFileDialog">
                <i class="bi bi-upload"></i>
                Importer des fichiers CSV
            </button>
            <button class="btn btn-info text-white" type="button" @click="openImageDialog">
                <i class="bi bi-folder"></i>
                Importer Dossier Images
            </button>
            <button class="btn btn-outline-secondary" type="button" @click="openFileDialog" aria-label="Add files">
                <i class="bi bi-plus-lg"></i>
            </button>
        </div>
        <div class="text-muted small">Drag and drop CSV files here</div>
    </div>
</template>

<style scoped>
.upload-zone {
    border-style: dashed;
    border-width: 2px;
}
</style>
