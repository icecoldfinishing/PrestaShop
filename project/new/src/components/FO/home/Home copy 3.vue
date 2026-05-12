<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { psGet, getXmlText } from '../../../utils/prestashop-api';

/**
 * CONFIGURATION
 */
const TARGET_PRODUCT_ID = 1; // Change l'ID ici ou lie-le à un input

const loading = ref(false);
const features = ref<string[]>([]);
const variants = ref<string[]>([]);

/**
 * UTILS : Nettoyage des IDs et du Texte multilingue
 */
const cleanId = (idField: any): string => {
    if (!idField) return '';
    if (typeof idField === 'object') {
        return String(idField['#text'] || idField['@_id'] || '').trim();
    }
    return String(idField).trim();
};

const extractText = (field: any): string => {
    if (!field) return '';
    const target = field.language ? field.language : field;
    return getXmlText(target);
};

/**
 * FONCTION PRINCIPALE : Récupère les specs et variantes d'un produit
 */
const loadProductDetails = async (productId: number) => {
    loading.value = true;
    try {
        // 1. CHARGEMENT DES DICTIONNAIRES (Pour transformer les IDs en noms lisibles)
        const [allFeaturesData, allGroupsData] = await Promise.all([
            psGet('product_features', null, { display: 'full' }),
            psGet('product_options', null, { display: 'full' })
        ]);

        const featureMap: Record<string, string> = {};
        [].concat(allFeaturesData?.prestashop?.product_features?.product_feature || []).forEach(f => {
            featureMap[cleanId(f.id)] = extractText(f.name);
        });

        const groupMap: Record<string, string> = {};
        [].concat(allGroupsData?.prestashop?.product_options?.product_option || []).forEach(g => {
            groupMap[cleanId(g.id)] = extractText(g.name);
        });

        // 2. RÉCUPÉRATION DU PRODUIT CIBLE
        const productResponse = await psGet('products', productId);
        const p = productResponse?.prestashop?.product;
        if (!p) throw new Error("Produit non trouvé");

        // --- PARTIE A : LES FEATURES (CARACTÉRISTIQUES) ---
        const pFeatures = [].concat(p.associations?.product_features?.product_feature || []);
        const resolvedFeatures = await Promise.all(pFeatures.map(async (pf) => {
            const fId = cleanId(pf.id_feature);
            const vId = cleanId(pf.id_feature_value);
            
            // On doit chercher le texte de la valeur spécifique
            const valData = await psGet('product_feature_values', vId);
            const valText = extractText(valData?.prestashop?.product_feature_value?.value);
            
            return `${featureMap[fId] || 'Spec'}: ${valText}`;
        }));

        // --- PARTIE B : LES VARIANTS (ATTRIBUTS DES DÉCLINAISONS) ---
        const pCombos = [].concat(p.associations?.combinations?.combination || []);
        const resolvedVariants: string[] = [];

        // On boucle sur chaque combinaison du produit
        for (const combo of pCombos) {
            const cId = cleanId(combo.id);
            const comboDetail = await psGet('combinations', cId);
            const attrValues = [].concat(comboDetail?.prestashop?.combination?.associations?.product_option_values?.product_option_value || []);
            
            // Pour chaque attribut dans la combinaison (ex: Taille + Couleur)
            const labelParts = await Promise.all(attrValues.map(async (av) => {
                const attrValId = cleanId(av.id);
                const attrValData = await psGet('product_option_values', attrValId);
                const avData = attrValData?.prestashop?.product_option_value;
                const gId = cleanId(avData?.id_attribute_group);
                
                return `${groupMap[gId] || 'Attribut'}: ${extractText(avData?.name)}`;
            }));
            
            resolvedVariants.push(labelParts.join(' / '));
        }

        features.value = resolvedFeatures;
        variants.value = resolvedVariants;

    } catch (err) {
        console.error('❌ Erreur lors du chargement du produit:', err);
    } finally {
        loading.value = false;
    }
};

onMounted(() => loadProductDetails(TARGET_PRODUCT_ID));
</script>

<template>
    <section class="product-debug">
        <header>
            <h1>Détails du Produit #{{ TARGET_PRODUCT_ID }}</h1>
            <button @click="loadProductDetails(TARGET_PRODUCT_ID)" :disabled="loading">
                {{ loading ? 'Chargement...' : 'Actualiser' }}
            </button>
        </header>

        <div v-if="loading" class="state-msg">Récupération des données API...</div>

        <div v-else class="grid">
            <!-- Caractéristiques Techniques -->
            <div class="card">
                <h2>Caractéristiques (Features)</h2>
                <ul v-if="features.length">
                    <li v-for="(f, i) in features" :key="'f'+i">{{ f }}</li>
                </ul>
                <p v-else class="empty">Aucune caractéristique.</p>
            </div>

            <!-- Déclinaisons -->
            <div class="card">
                <h2>Variantes disponibles</h2>
                <ul v-if="variants.length">
                    <li v-for="(v, i) in variants" :key="'v'+i" class="variant-item">
                        {{ v }}
                    </li>
                </ul>
                <p v-else class="empty">Ce produit n'a pas de déclinaisons.</p>
            </div>
        </div>
    </section>
</template>

<style scoped>
.product-debug {
    padding: 30px;
    font-family: sans-serif;
    max-width: 1000px;
    margin: 0 auto;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #eee;
    margin-bottom: 20px;
}

.grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

h2 {
    font-size: 1.1rem;
    color: #2c3e50;
    margin-top: 0;
}

ul {
    list-style: none;
    padding: 0;
}

li {
    padding: 10px;
    background: #f8f9fa;
    margin-bottom: 8px;
    border-radius: 4px;
    font-size: 0.9rem;
    border-left: 4px solid #3498db;
}

.variant-item {
    border-left-color: #e67e22;
}

.empty {
    color: #999;
    font-style: italic;
}

.state-msg {
    text-align: center;
    padding: 40px;
    color: #666;
}

button {
    padding: 8px 16px;
    cursor: pointer;
    background: #3498db;
    color: white;
    border: none;
    border-radius: 4px;
}

button:disabled { background: #ccc; }
</style>