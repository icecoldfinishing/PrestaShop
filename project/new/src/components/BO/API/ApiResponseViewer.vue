<template>
  <section class="api-viewer">
    <h2>API Response Viewer</h2>

    <form class="viewer-form" @submit.prevent="handleFetch">
      <label>
        Resource
        <input v-model="resource" type="text" placeholder="customers" />
      </label>

      <label>
        ID (optionnel)
        <input v-model="resourceId" type="text" placeholder="42" />
      </label>

      <label>
        Query params (JSON)
        <textarea
          v-model="paramsText"
          rows="5"
          placeholder='{"display":"[id,email]"}'
        ></textarea>
      </label>

      <button type="submit" :disabled="isLoading">
        {{ isLoading ? 'Chargement...' : 'Charger la reponse API' }}
      </button>
    </form>

    <p v-if="errorMessage" class="error">{{ errorMessage }}</p>

    <pre v-if="formattedResponse" class="result">{{ formattedResponse }}</pre>
  </section>
</template>

<script setup>
import { ref } from 'vue';
import { psGet } from '../../../utils/prestashop-api';

const resource = ref('customers');
const resourceId = ref('');
const paramsText = ref('{"display":"[id,email]"}');
const formattedResponse = ref('');
const isLoading = ref(false);
const errorMessage = ref('');

function parseParams() {
  if (!paramsText.value.trim()) {
    return {};
  }

  try {
    const parsed = JSON.parse(paramsText.value);
    return parsed && typeof parsed === 'object' ? parsed : {};
  } catch {
    throw new Error('Le JSON des query params est invalide.');
  }
}

async function handleFetch() {
  errorMessage.value = '';
  formattedResponse.value = '';

  if (!resource.value.trim()) {
    errorMessage.value = 'La resource est obligatoire.';
    return;
  }

  isLoading.value = true;

  try {
    const queryParams = parseParams();
    const data = await psGet(resource.value.trim(), resourceId.value.trim(), queryParams);
    formattedResponse.value = JSON.stringify(data, null, 2);
  } catch (error) {
    errorMessage.value = error?.message || 'Erreur inconnue pendant l appel API.';
  } finally {
    isLoading.value = false;
  }
}
</script>

<style scoped>
.api-viewer {
  margin: 24px auto;
  width: min(920px, 96%);
  text-align: left;
}

.viewer-form {
  display: grid;
  gap: 12px;
}

label {
  display: grid;
  gap: 6px;
  font-weight: 600;
}

input,
textarea,
button {
  font: inherit;
  border: 1px solid #c8c8c8;
  border-radius: 8px;
  padding: 10px 12px;
}

button {
  cursor: pointer;
  background: #0b5fff;
  color: #fff;
  border-color: #0b5fff;
}

button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.error {
  color: #be123c;
  font-weight: 600;
}

.result {
  background: #111827;
  color: #f9fafb;
  border-radius: 10px;
  padding: 14px;
  overflow: auto;
  max-height: 380px;
}
</style>