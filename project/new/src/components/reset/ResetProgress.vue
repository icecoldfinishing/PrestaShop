<script setup lang="ts">
import type { ResetProgress } from '../../types/reset.types';
import { computed } from 'vue';

const props = defineProps<{
  progress: ResetProgress;
}>();

const emit = defineEmits<{
  (e: 'pause'): void;
  (e: 'resume'): void;
  (e: 'cancel'): void;
}>();

const statusLabel = computed(() => {
  switch (props.progress.status) {
    case 'counting': return 'Comptage des éléments…';
    case 'running': return 'Suppression en cours…';
    case 'paused': return 'En pause';
    case 'cancelled': return 'Annulé';
    case 'completed': return 'Terminé';
    default: return '';
  }
});

const statusIcon = computed(() => {
  switch (props.progress.status) {
    case 'counting': return 'bi-hourglass-split';
    case 'running': return 'bi-arrow-repeat spin-animation';
    case 'paused': return 'bi-pause-circle';
    case 'cancelled': return 'bi-x-circle';
    case 'completed': return 'bi-check-circle';
    default: return '';
  }
});

const progressBarClass = computed(() => {
  switch (props.progress.status) {
    case 'running':
    case 'counting':
      return 'bg-primary progress-bar-striped progress-bar-animated';
    case 'paused':
      return 'bg-warning';
    case 'cancelled':
      return 'bg-danger';
    case 'completed':
      return 'bg-success';
    default:
      return 'bg-primary';
  }
});

const deletedCount = computed(() =>
  props.progress.resourceProgress.reduce((sum, r) => sum + r.success, 0)
);

const failedCount = computed(() =>
  props.progress.resourceProgress.reduce((sum, r) => sum + r.failed, 0)
);
</script>

<template>
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
          <h5 class="mb-1">
            <i :class="statusIcon" class="me-2"></i>
            {{ statusLabel }}
          </h5>
          <div v-if="progress.currentModuleId" class="text-muted small">
            Module : <strong>{{ progress.currentModuleId }}</strong>
            <span v-if="progress.currentResource">
              &nbsp;→&nbsp;Ressource : <strong>{{ progress.currentResource }}</strong>
            </span>
          </div>
        </div>

        <div class="d-flex gap-2">
          <button
            v-if="progress.status === 'running'"
            class="btn btn-warning btn-sm"
            @click="emit('pause')"
          >
            <i class="bi bi-pause-fill me-1"></i>Pause
          </button>
          <button
            v-if="progress.status === 'paused'"
            class="btn btn-primary btn-sm"
            @click="emit('resume')"
          >
            <i class="bi bi-play-fill me-1"></i>Reprendre
          </button>
          <button
            v-if="progress.status === 'running' || progress.status === 'paused'"
            class="btn btn-outline-danger btn-sm"
            @click="emit('cancel')"
          >
            <i class="bi bi-x-circle me-1"></i>Annuler
          </button>
        </div>
      </div>

      <!-- Global progress bar -->
      <div class="progress mb-3" style="height: 24px;">
        <div
          class="progress-bar"
          :class="progressBarClass"
          role="progressbar"
          :style="{ width: progress.percent + '%' }"
          :aria-valuenow="progress.percent"
          aria-valuemin="0"
          aria-valuemax="100"
        >
          {{ progress.percent }}%
        </div>
      </div>

      <!-- Stats row -->
      <div class="row g-2 text-center">
        <div class="col-3">
          <div class="stat-card bg-light rounded p-2">
            <div class="fw-bold text-primary fs-5">{{ progress.totalItems }}</div>
            <div class="text-muted small">Total</div>
          </div>
        </div>
        <div class="col-3">
          <div class="stat-card bg-light rounded p-2">
            <div class="fw-bold text-info fs-5">{{ progress.processedItems }}</div>
            <div class="text-muted small">Traités</div>
          </div>
        </div>
        <div class="col-3">
          <div class="stat-card bg-light rounded p-2">
            <div class="fw-bold text-success fs-5">{{ deletedCount }}</div>
            <div class="text-muted small">Supprimés</div>
          </div>
        </div>
        <div class="col-3">
          <div class="stat-card bg-light rounded p-2">
            <div class="fw-bold text-danger fs-5">{{ failedCount }}</div>
            <div class="text-muted small">Échoués</div>
          </div>
        </div>
      </div>

      <!-- Per-resource breakdown -->
      <div v-if="progress.resourceProgress.length > 0" class="mt-3">
        <h6 class="text-muted small fw-semibold mb-2">Détail par ressource</h6>
        <div class="table-responsive">
          <table class="table table-sm table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th>Ressource</th>
                <th class="text-center" style="width: 80px;">Total</th>
                <th class="text-center" style="width: 80px;">OK</th>
                <th class="text-center" style="width: 80px;">Erreurs</th>
                <th style="width: 200px;">Progression</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="rp in progress.resourceProgress" :key="rp.apiResource">
                <td>
                  <code>{{ rp.apiResource }}</code>
                </td>
                <td class="text-center">{{ rp.total }}</td>
                <td class="text-center text-success">{{ rp.success }}</td>
                <td class="text-center text-danger">{{ rp.failed }}</td>
                <td>
                  <div class="progress" style="height: 14px;">
                    <div
                      class="progress-bar bg-success"
                      :style="{ width: (rp.total ? (rp.processed / rp.total) * 100 : 0) + '%' }"
                    ></div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.spin-animation {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.stat-card {
  transition: transform 0.2s ease;
}
.stat-card:hover {
  transform: scale(1.03);
}
</style>
