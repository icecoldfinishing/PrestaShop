<script setup lang="ts">
import type { ResetModuleState } from '../../types/reset.types';
import { getAllTablesForModule } from '../../config/reset-modules.config';
import { ref } from 'vue';

defineProps<{
  modules: ResetModuleState[];
  disabled?: boolean;
}>();

const emit = defineEmits<{
  (e: 'toggle', id: string): void;
  (e: 'select-all'): void;
  (e: 'deselect-all'): void;
}>();

const expandedModules = ref<Set<string>>(new Set());

const toggleExpand = (id: string) => {
  if (expandedModules.value.has(id)) {
    expandedModules.value.delete(id);
  } else {
    expandedModules.value.add(id);
  }
  expandedModules.value = new Set(expandedModules.value);
};
</script>

<template>
  <div class="card border-0 shadow-sm">
    <div class="card-body">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
          <h5 class="mb-1">
            <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>
            Modules de réinitialisation
          </h5>
          <div class="text-muted small">
            Sélectionnez les modules à réinitialiser. Les tables liées seront automatiquement nettoyées.
          </div>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-outline-primary btn-sm" :disabled="disabled" @click="emit('select-all')">
            <i class="bi bi-check-all me-1"></i>Tout sélectionner
          </button>
          <button class="btn btn-outline-secondary btn-sm" :disabled="disabled" @click="emit('deselect-all')">
            <i class="bi bi-x-lg me-1"></i>Tout désélectionner
          </button>
        </div>
      </div>

      <div class="row g-3">
        <div v-for="mod in modules" :key="mod.id" class="col-12 col-lg-4">
          <div
            class="module-card card h-100"
            :class="{
              'border-primary bg-primary-subtle': mod.checked,
              'border-secondary-subtle': !mod.checked,
              'opacity-50': disabled
            }"
          >
            <div class="card-body">
              <!-- Header with checkbox -->
              <div class="d-flex align-items-start gap-3">
                <div class="form-check mt-1">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    :id="'module-' + mod.id"
                    :checked="mod.checked"
                    :disabled="disabled"
                    @change="emit('toggle', mod.id)"
                  />
                </div>
                <div class="flex-grow-1">
                  <label
                    :for="'module-' + mod.id"
                    class="form-check-label d-flex align-items-center gap-2 fw-semibold mb-1"
                    role="button"
                  >
                    <i :class="mod.config.icon" class="fs-5 text-primary"></i>
                    {{ mod.config.label }}
                  </label>
                  <p class="text-muted small mb-2">{{ mod.config.description }}</p>

                  <!-- Badges -->
                  <div class="d-flex gap-2 flex-wrap mb-2">
                    <span class="badge bg-primary rounded-pill">
                      <i class="bi bi-plug me-1"></i>{{ mod.config.apiResources.length }} API
                    </span>
                    <span class="badge bg-secondary rounded-pill">
                      <i class="bi bi-link-45deg me-1"></i>{{ mod.config.cascadeTables.length }} cascade
                    </span>
                    <span class="badge bg-info text-dark rounded-pill">
                      <i class="bi bi-table me-1"></i>{{ getAllTablesForModule(mod.id).length }} tables
                    </span>
                  </div>

                  <!-- Expand button -->
                  <button
                    class="btn btn-link btn-sm p-0 text-decoration-none"
                    @click.prevent="toggleExpand(mod.id)"
                  >
                    <i :class="expandedModules.has(mod.id) ? 'bi-chevron-up' : 'bi-chevron-down'" class="me-1"></i>
                    {{ expandedModules.has(mod.id) ? 'Masquer' : 'Voir' }} les tables
                  </button>

                  <!-- Table list (expandable) -->
                  <div v-if="expandedModules.has(mod.id)" class="mt-2">
                    <div class="small">
                      <div class="fw-semibold text-primary mb-1">
                        <i class="bi bi-plug me-1"></i>Ressources API :
                      </div>
                      <ul class="list-unstyled ms-3 mb-2">
                        <li v-for="res in mod.config.apiResources" :key="res.apiResource" class="mb-1">
                          <code class="text-dark">{{ res.table }}</code>
                          <span class="text-muted"> → </span>
                          <span class="badge bg-light text-primary border">{{ res.apiResource }}</span>
                        </li>
                      </ul>

                      <div v-if="mod.config.cascadeTables.length > 0">
                        <div class="fw-semibold text-secondary mb-1">
                          <i class="bi bi-link-45deg me-1"></i>Tables cascade :
                        </div>
                        <ul class="list-unstyled ms-3">
                          <li v-for="ct in mod.config.cascadeTables" :key="ct.table" class="mb-1">
                            <code class="text-muted">{{ ct.table }}</code>
                            <span class="text-muted small"> (via {{ ct.cascadeParent }})</span>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.module-card {
  transition: all 0.25s ease;
  border-width: 2px;
}
.module-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08) !important;
}
.form-check-input {
  width: 1.25em;
  height: 1.25em;
}
.bg-primary-subtle {
  background-color: rgba(13, 110, 253, 0.05) !important;
}
</style>
