import { computed, ref } from 'vue';
import { RESET_MODULES, RESET_MODULE_IDS, getAllTablesForModule } from '../config/reset-modules.config';
import { ResetModuleService } from '../services/ResetModule.service';
import type {
  ResetModuleState,
  ResetProgress,
  ResetReport,
} from '../types/reset.types';

// ---------------------------------------------------------------------------
// Composable: useDataReset
// ---------------------------------------------------------------------------

/**
 * Vue composable that provides the full reactive state and actions
 * for the data reset feature.
 *
 * Usage in a component:
 * ```ts
 * const { modules, progress, report, startReset, ... } = useDataReset();
 * ```
 */
export function useDataReset() {
  // --- Reactive state ---

  const modules = ref<ResetModuleState[]>(
    RESET_MODULE_IDS.map((id) => ({
      id,
      config: RESET_MODULES[id],
      checked: RESET_MODULES[id].defaultChecked,
    }))
  );

  const progress = ref<ResetProgress>({
    status: 'idle',
    currentModuleId: null,
    currentResource: null,
    percent: 0,
    totalItems: 0,
    processedItems: 0,
    resourceProgress: [],
  });

  const report = ref<ResetReport | null>(null);
  const activeService = ref<ResetModuleService | null>(null);

  // --- Computed ---

  const selectedModuleIds = computed(() =>
    modules.value.filter((m) => m.checked).map((m) => m.id)
  );

  const canStart = computed(() =>
    selectedModuleIds.value.length > 0 && progress.value.status === 'idle'
  );

  const isRunning = computed(() =>
    ['counting', 'running', 'paused'].includes(progress.value.status)
  );

  const selectedModuleSummary = computed(() =>
    modules.value
      .filter((m) => m.checked)
      .map((m) => ({
        id: m.id,
        label: m.config.label,
        tables: getAllTablesForModule(m.id),
        apiResourceCount: m.config.apiResources.length,
        cascadeTableCount: m.config.cascadeTables.length,
      }))
  );

  // --- Actions ---

  const toggleModule = (id: string) => {
    const mod = modules.value.find((m) => m.id === id);
    if (mod) {
      mod.checked = !mod.checked;
    }
  };

  const selectAll = () => {
    modules.value.forEach((m) => {
      m.checked = true;
    });
  };

  const deselectAll = () => {
    modules.value.forEach((m) => {
      m.checked = false;
    });
  };

  const startReset = async () => {
    if (!canStart.value) return;

    report.value = null;
    progress.value = {
      status: 'counting',
      currentModuleId: null,
      currentResource: null,
      percent: 0,
      totalItems: 0,
      processedItems: 0,
      resourceProgress: [],
    };

    const service = new ResetModuleService();
    activeService.value = service;

    const result = await service.run(selectedModuleIds.value, {
      onProgress: (p) => {
        progress.value = p;
      },
    });

    report.value = result;
    progress.value = {
      ...progress.value,
      status: result.status === 'cancelled' ? 'cancelled' : 'completed',
    };
    activeService.value = null;
  };

  const pauseReset = () => {
    if (!activeService.value) return;
    activeService.value.pause();
    progress.value = { ...progress.value, status: 'paused' };
  };

  const resumeReset = () => {
    if (!activeService.value) return;
    activeService.value.resume();
    progress.value = { ...progress.value, status: 'running' };
  };

  const cancelReset = () => {
    if (!activeService.value) return;
    activeService.value.cancel();
    progress.value = { ...progress.value, status: 'cancelled' };
  };

  const resetState = () => {
    report.value = null;
    progress.value = {
      status: 'idle',
      currentModuleId: null,
      currentResource: null,
      percent: 0,
      totalItems: 0,
      processedItems: 0,
      resourceProgress: [],
    };
  };

  const exportReportJson = () => {
    if (!report.value) return;
    const content = JSON.stringify(report.value, null, 2);
    const blob = new Blob([content], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `reset-report-${Date.now()}.json`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  };

  return {
    // State
    modules,
    progress,
    report,

    // Computed
    selectedModuleIds,
    canStart,
    isRunning,
    selectedModuleSummary,

    // Actions
    toggleModule,
    selectAll,
    deselectAll,
    startReset,
    pauseReset,
    resumeReset,
    cancelReset,
    resetState,
    exportReportJson,
  };
}
