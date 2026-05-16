<script setup lang="ts">
import { ref } from "vue";
// Correction de l'import pour utiliser la fonction dédiée aux clients uniquement
import { runCustomerOnlyImport } from '../../../services/import/orders/OrderImport.service'

const logs = ref<string[]>([]);
const loading = ref(false);

async function startTest() {
    loading.value = true;
    logs.value = [];

    const hardData = [
        {
            date: "12/05/2026",
            nom: "haha",
            email: "haha.test@yopmail.com",
            pwd: "Test123456!",
            adresse: "Antananarivo Madagascar"
        }
    ];

    try {
        // Utilisation de runCustomerOnlyImport au lieu de l'ancienne fonction
        const results = await runCustomerOnlyImport(
            hardData,
            (msg) => {
                logs.value.push(msg);
                console.log(msg);
            }
        );

        console.log("RESULTS =", results);

    } catch (e: any) {
        logs.value.push("ERREUR GLOBALE: " + (e?.message || e));
    }

    loading.value = false;
}
</script>

<template>
    <div class="container py-4">

        <div class="card shadow-sm">
            <div class="card-body">

                <h3 class="mb-4">
                    Test Import Customer Only
                </h3>

                <button class="btn btn-primary" @click="startTest" :disabled="loading">
                    <span v-if="loading">
                        Import en cours...
                    </span>

                    <span v-else>
                        Lancer le test
                    </span>
                </button>

                <hr class="my-4" />

                <div style="
            background:#111;
            color:#00ff66;
            padding:15px;
            border-radius:8px;
            min-height:300px;
            overflow:auto;
            font-family:monospace;
            white-space:pre-wrap;
          ">
                    <div v-for="(log, index) in logs" :key="index">
                        {{ log }}
                    </div>
                </div>

            </div>
        </div>

    </div>
</template>