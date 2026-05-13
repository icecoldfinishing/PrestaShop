<script setup lang="ts">
import { ref } from 'vue';
import ModuleSelector from './ModuleSelector.vue';
import ResetProgress from './ResetProgress.vue';
import ResetReport from './ResetReport.vue';
import { useDataReset } from '../../../composables/useDataReset';

const {
  modules,
  progress,
  report,
  selectedModuleIds,
  canStart,
  isRunning,
  selectedModuleSummary,
  toggleModule,
  selectAll,
  deselectAll,
  startReset,
  pauseReset,
  resumeReset,
  cancelReset,
  resetState,
  exportReportJson,
} = useDataReset();

const showConfirmModal = ref(false);

const openConfirmModal = () => {
  showConfirmModal.value = true;
};

const confirmReset = () => {
  showConfirmModal.value = false;
  startReset();
};

const cancelConfirm = () => {
  showConfirmModal.value = false;
};

const handleNewReset = () => {
  resetState();
};
</script>

<template>
  <div class="container-fluid">
    <!-- Page header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
      <div>
        <h2 class="fw-bold mb-1">
          <i class="bi bi-arrow-counterclockwise me-2 text-danger"></i>
          Réinitialisation des données
        </h2>
        <p class="text-muted mb-0">
          Supprimez sélectivement les données de votre boutique PrestaShop par module.
          Les tables liées et dépendances sont gérées automatiquement.
        </p>
      </div>
    </div>

    <!-- Warning banner -->
    <div class="alert alert-warning d-flex align-items-start gap-2 mb-4">
      <i class="bi bi-exclamation-triangle-fill fs-4 mt-1"></i>
      <div>
        <strong>Attention !</strong> Cette opération est irréversible.
        Les données supprimées ne pourront pas être récupérées.
        Assurez-vous d'avoir une sauvegarde avant de continuer.
      </div>
    </div>

    <!-- Module selector -->
    <ModuleSelector
      :modules="modules"
      :disabled="isRunning"
      class="mb-4"
      @toggle="toggleModule"
      @select-all="selectAll"
      @deselect-all="deselectAll"
    />

    <!-- Action buttons -->
    <div v-if="progress.status === 'idle'" class="d-flex justify-content-end mb-4">
      <button
        class="btn btn-danger btn-lg px-4"
        :disabled="!canStart"
        @click="openConfirmModal"
      >
        <i class="bi bi-trash3 me-2"></i>
        Réinitialiser ({{ selectedModuleIds.length }} module{{ selectedModuleIds.length > 1 ? 's' : '' }})
      </button>
    </div>

    <!-- Progress -->
    <ResetProgress
      v-if="progress.status !== 'idle'"
      :progress="progress"
      class="mb-4"
      @pause="pauseReset"
      @resume="resumeReset"
      @cancel="cancelReset"
    />

    <!-- Report -->
    <ResetReport
      v-if="report"
      :report="report"
      class="mb-4"
      @export-json="exportReportJson"
      @reset="handleNewReset"
    />

    <!-- Confirmation modal -->
    <Teleport to="body">
      <div
        v-if="showConfirmModal"
        class="modal d-block"
        tabindex="-1"
        style="background: rgba(0,0,0,0.5);"
        @click.self="cancelConfirm"
      >
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-danger">
            <div class="modal-header bg-danger text-white">
              <h5 class="modal-title">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Confirmer la réinitialisation
              </h5>
              <button type="button" class="btn-close btn-close-white" @click="cancelConfirm"></button>
            </div>
            <div class="modal-body">
              <p class="mb-3">
                Vous êtes sur le point de supprimer <strong>toutes les données</strong> des modules suivants :
              </p>
              <ul class="list-group list-group-flush mb-3">
                <li
                  v-for="mod in selectedModuleSummary"
                  :key="mod.id"
                  class="list-group-item d-flex justify-content-between align-items-center"
                >
                  <span>
                    <i class="bi bi-box me-2 text-primary"></i>
                    {{ mod.label }}
                  </span>
                  <span class="badge bg-danger rounded-pill">{{ mod.tables.length }} tables</span>
                </li>
              </ul>
              <div class="alert alert-danger mb-0">
                <i class="bi bi-exclamation-octagon me-1"></i>
                <strong>Cette action est irréversible.</strong>
                Toutes les données sélectionnées seront définitivement supprimées via l'API PrestaShop.
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="cancelConfirm">
                <i class="bi bi-x-lg me-1"></i>Annuler
              </button>
              <button type="button" class="btn btn-danger" @click="confirmReset">
                <i class="bi bi-trash3 me-1"></i>Oui, réinitialiser
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>
