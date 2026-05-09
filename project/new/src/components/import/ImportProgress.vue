<script setup lang="ts">
import type { ImportProgress } from '../../types/import.types';

const props = defineProps<{
    progress: ImportProgress;
}>();

const emit = defineEmits<{
    (e: 'pause'): void;
    (e: 'resume'): void;
    (e: 'cancel'): void;
}>();
</script>

<template>
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                <div>
                    <h5 class="mb-1">Import progress</h5>
                    <div class="text-muted small">
                        {{ progress.processed }} / {{ progress.total }} processed | Success: {{ progress.success }} |
                        Errors: {{ progress.failed }}
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button v-if="progress.status === 'running'" class="btn btn-outline-secondary"
                        @click="emit('pause')">
                        Pause
                    </button>
                    <button v-if="progress.status === 'paused'" class="btn btn-outline-secondary"
                        @click="emit('resume')">
                        Resume
                    </button>
                    <button v-if="progress.status === 'running' || progress.status === 'paused'"
                        class="btn btn-outline-danger" @click="emit('cancel')">
                        Cancel
                    </button>
                </div>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" :style="{ width: progress.percent + '%' }">
                    {{ progress.percent }}%
                </div>
            </div>
        </div>
    </div>
</template>
