<script setup lang="ts">
import type { ResetReport } from '../../types/reset.types';
import { computed } from 'vue';

const props = defineProps<{
  report: ResetReport;
}>();

const emit = defineEmits<{
  (e: 'export-json'): void;
  (e: 'reset'): void;
}>();

const duration = computed(() => {
  const start = new Date(props.report.startedAt).getTime();
  const end = new Date(props.report.finishedAt).getTime();
  const ms = end - start;
  if (ms < 1000) return `${ms}ms`;
  const seconds = Math.floor(ms / 1000);
  if (seconds < 60) return `${seconds}s`;
  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = seconds % 60;
  return `${minutes}m ${remainingSeconds}s`;
});

const alertClass = computed(() => {
  if (props.report.status === 'cancelled') return 'alert-warning';
  if (props.report.totalFailed > 0) return 'alert-danger';
  return 'alert-success';
});

const alertIcon = computed(() => {
  if (props.report.status === 'cancelled') return 'bi-exclamation-triangle-fill';
  if (props.report.totalFailed > 0) return 'bi-exclamation-circle-fill';
  return 'bi-check-circle-fill';
});
</script>

<template>
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h5 class="mb-0">
          <i class="bi bi-clipboard-check me-2 text-primary"></i>
          Rapport de réinitialisation
        </h5>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-primary btn-sm" @click="emit('export-json')">
            <i class="bi bi-download me-1"></i>Export JSON
          </button>
          <button class="btn btn-primary btn-sm" @click="emit('reset')">
            <i class="bi bi-arrow-counterclockwise me-1"></i>Nouvelle réinitialisation
          </button>
        </div>
      </div>

      <!-- Summary alert -->
      <div class="alert d-flex align-items-center gap-2" :class="alertClass">
        <i :class="alertIcon" class="fs-4"></i>
        <div>
          <strong v-if="report.status === 'completed' && report.totalFailed === 0">
            Réinitialisation terminée avec succès !
          </strong>
          <strong v-else-if="report.status === 'cancelled'">
            Réinitialisation annulée.
          </strong>
          <strong v-else>
            Réinitialisation terminée avec {{ report.totalFailed }} erreur(s).
          </strong>
          <span class="ms-2 text-muted">Durée : {{ duration }}</span>
        </div>
      </div>

      <!-- Global stats -->
      <div class="row g-3 mb-4">
        <div class="col-4">
          <div class="text-center bg-light rounded p-3">
            <div class="fw-bold text-success fs-3">{{ report.totalDeleted }}</div>
            <div class="text-muted small">Éléments supprimés</div>
          </div>
        </div>
        <div class="col-4">
          <div class="text-center bg-light rounded p-3">
            <div class="fw-bold text-danger fs-3">{{ report.totalFailed }}</div>
            <div class="text-muted small">Suppressions échouées</div>
          </div>
        </div>
        <div class="col-4">
          <div class="text-center bg-light rounded p-3">
            <div class="fw-bold text-primary fs-3">{{ report.modules.length }}</div>
            <div class="text-muted small">Modules traités</div>
          </div>
        </div>
      </div>

      <!-- Per-module details -->
      <h6 class="fw-semibold mb-3">Détail par module</h6>
      <div v-for="mod in report.modules" :key="mod.moduleId" class="mb-3">
        <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between py-2">
            <span class="fw-semibold">
              <i class="bi bi-box me-1"></i>{{ mod.moduleLabel }}
            </span>
            <div>
              <span class="badge bg-success me-1">{{ mod.totalDeleted }} supprimés</span>
              <span v-if="mod.totalFailed > 0" class="badge bg-danger">{{ mod.totalFailed }} échoués</span>
            </div>
          </div>
          <div class="card-body p-0">
            <table class="table table-sm mb-0">
              <thead class="table-light">
                <tr>
                  <th>Ressource API</th>
                  <th>Table</th>
                  <th class="text-center">Total</th>
                  <th class="text-center">Supprimés</th>
                  <th class="text-center">Échoués</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="res in mod.resources" :key="res.apiResource">
                  <td><code>{{ res.apiResource }}</code></td>
                  <td><code class="text-muted">{{ res.table }}</code></td>
                  <td class="text-center">{{ res.total }}</td>
                  <td class="text-center text-success">{{ res.deleted }}</td>
                  <td class="text-center" :class="res.failed > 0 ? 'text-danger fw-bold' : ''">
                    {{ res.failed }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Error list -->
      <div v-if="report.errors.length > 0" class="mt-3">
        <h6 class="fw-semibold text-danger mb-2">
          <i class="bi bi-exclamation-triangle me-1"></i>
          Erreurs ({{ report.errors.length }})
        </h6>
        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
          <table class="table table-sm table-bordered mb-0">
            <thead class="table-danger">
              <tr>
                <th>Module</th>
                <th>Ressource</th>
                <th>ID</th>
                <th>Message</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(err, idx) in report.errors" :key="idx">
                <td>{{ err.moduleId }}</td>
                <td><code>{{ err.apiResource }}</code></td>
                <td>{{ err.itemId }}</td>
                <td class="text-danger">{{ err.message }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>
