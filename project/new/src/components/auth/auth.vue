<template>
  <div>
    <form @submit.prevent="handleLogin">
      <input v-model="credentials.email" type="email" placeholder="Email" />
      <input v-model="credentials.password" type="password" placeholder="Mot de passe" />
      <button type="submit">Envoyer</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { getXmlText, psGet } from '../../utils/prestashop-api';

const credentials = ref({
  email: '',
  password: ''
});

const handleLogin = async () => {
  try {
    const emailToFilter = credentials.value.email;

    if (!emailToFilter) {
      alert('Veuillez renseigner votre email.');
      return;
    }

    const data = await psGet('customers', '', {
      'filter[email]': `[${emailToFilter}]`,
      display: '[email,passwd]',
    });

    const customerData = data?.prestashop?.customers?.customer;
    const customer = Array.isArray(customerData) ? customerData[0] : customerData;

    if (!customer) {
      alert('Identifiants incorrects (Email non trouve).');
      return;
    }

    const dbEmail = getXmlText(customer.email);
    const dbPasswd = getXmlText(customer.passwd);

    console.log('Trouve :', dbEmail, 'Hash :', dbPasswd);

    // Le champ passwd recu depuis PrestaShop est normalement un hash.
    if (credentials.value.password === dbPasswd) {
      alert('Connexion reussie !');
    } else {
      alert('Mot de passe incorrect.');
    }
  } catch (error) {
    console.error('Erreur technique :', error);
    alert('Impossible de contacter le serveur.');
  }
};
</script>