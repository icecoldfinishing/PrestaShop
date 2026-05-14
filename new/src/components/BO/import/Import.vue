<script setup lang="ts">
import { ref } from 'vue'

import { runProductImport } from '../../../services/import/products/ProductLoadCsv'
import { runCombinationImport } from '../../../services/import/combinations/CombinationLoadCsv'
import { runOrderImport } from '../../../services/import/orders/OrderLoadCsv'
import { ImageImportService } from '../../../services/import/images/ImageImport.service'

/* =========================
    UI STATE
========================= */
const loading = ref(false)
const logs = ref<string[]>([])

/* =========================
    SELECTED IMPORTS (ALL TRUE BY DEFAULT)
========================= */
const selected = ref({
    products: true,
    combinations: true,
    orders: true,
    images: true
})

/* =========================
    FILES
========================= */
const files = ref<{
    products: File | null
    combinations: File | null
    orders: File | null
    images: File | null
}>({
    products: null,
    combinations: null,
    orders: null,
    images: null
})

/* =========================
    LOGS
========================= */
const addLog = (msg: string) => logs.value.unshift(msg)

/* =========================
    FILE HANDLER
========================= */
const onFileChange = (type: keyof typeof files.value, e: Event) => {
    const target = e.target as HTMLInputElement
    if (!target.files?.[0]) return

    files.value[type] = target.files[0]
    addLog(`${type.toUpperCase()} file selected`)
}

/* =========================
    ORDER FIXED EXECUTION ORDER
========================= */
const executionOrder: (keyof typeof selected.value)[] = [
    'products',
    'combinations',
    'orders',
    'images'
]

/* =========================
    IMPORT ENGINE
========================= */
async function startImport() {
    loading.value = true
    logs.value = ['START IMPORT PROCESS']

    try {
        for (const type of executionOrder) {

            if (!selected.value[type]) continue

            const file = files.value[type]

            /* ================= PRODUCTS ================= */
            if (type === 'products') {
                if (!file) {
                    addLog('PRODUCTS file missing')
                    continue
                }

                addLog('IMPORT PRODUCTS...')
                await runProductImport(file, addLog)
                addLog('PRODUCTS DONE')
            }

            /* ================= COMBINATIONS ================= */
            if (type === 'combinations') {
                if (!file) {
                    addLog('COMBINATIONS file missing')
                    continue
                }

                addLog('IMPORT COMBINATIONS...')
                await runCombinationImport(file, addLog)
                addLog('COMBINATIONS DONE')
            }

            /* ================= ORDERS ================= */
            if (type === 'orders') {
                if (!file) {
                    addLog('ORDERS file missing')
                    continue
                }

                addLog('IMPORT ORDERS...')
                await runOrderImport(file, addLog)
                addLog('ORDERS DONE')
            }

            /* ================= IMAGES ================= */
            if (type === 'images') {
                if (!file) {
                    addLog('IMAGES ZIP missing')
                    continue
                }

                addLog('IMPORT IMAGES...')
                await ImageImportService.processZip(file, addLog)
                addLog('IMAGES DONE')
            }
        }

        addLog('ALL IMPORTS FINISHED')

    } catch (e: any) {
        addLog(`ERROR: ${e.message}`)
    } finally {
        loading.value = false
    }
}
</script>
<template>
<div class="container py-4">

    <div class="card shadow-sm">
        <div class="card-body">

            <h3 class="text-center mb-1">Import System</h3>
            <p class="text-center text-muted mb-4">
                Products • Combinations • Orders • Images
            </p>

            <!-- CHECKBOX GRID -->
            <div class="row g-3 mb-4">

                <div class="col-md-3">
                    <div class="form-check border rounded p-3">
                        <input class="form-check-input"
                            type="checkbox"
                            v-model="selected.products">
                        <label class="form-check-label">Products</label>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-check border rounded p-3">
                        <input class="form-check-input"
                            type="checkbox"
                            v-model="selected.combinations">
                        <label class="form-check-label">Combinations</label>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-check border rounded p-3">
                        <input class="form-check-input"
                            type="checkbox"
                            v-model="selected.orders">
                        <label class="form-check-label">Orders</label>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-check border rounded p-3">
                        <input class="form-check-input"
                            type="checkbox"
                            v-model="selected.images">
                        <label class="form-check-label">Images</label>
                    </div>
                </div>

            </div>

            <!-- FILE INPUTS -->
            <div class="row g-3 mb-4">

                <div class="col-md-3">
                    <input type="file" class="form-control"
                        @change="onFileChange('products', $event)">
                </div>

                <div class="col-md-3">
                    <input type="file" class="form-control"
                        @change="onFileChange('combinations', $event)">
                </div>

                <div class="col-md-3">
                    <input type="file" class="form-control"
                        @change="onFileChange('orders', $event)">
                </div>

                <div class="col-md-3">
                    <input type="file" class="form-control"
                        accept=".zip"
                        @change="onFileChange('images', $event)">
                </div>

            </div>

            <!-- BUTTON -->
            <button class="btn btn-primary w-100 py-2"
                :disabled="loading"
                @click="startImport">

                {{ loading ? 'Importing...' : 'Start Import' }}

            </button>

            <!-- LOGS -->
            <div class="mt-4 bg-dark text-light p-3 rounded"
                style="height: 250px; overflow:auto; font-size:12px">

                <div v-for="(l, i) in logs" :key="i">
                    [{{ new Date().toLocaleTimeString() }}] {{ l }}
                </div>

            </div>

        </div>
    </div>

</div>
</template>