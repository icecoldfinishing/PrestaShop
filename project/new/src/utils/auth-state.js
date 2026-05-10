import { ref, computed } from 'vue';

// État réactif pour le client connecté
export const loggedCustomer = ref(JSON.parse(localStorage.getItem('loggedCustomer') || 'null'));

// Getters utiles
export const isLoggedIn = computed(() => !!loggedCustomer.value);

/**
 * Définit le client connecté et persiste dans localStorage
 * @param {Object|null} customer 
 */
export function setLoggedCustomer(customer) {
    loggedCustomer.value = customer;
    if (customer) {
        localStorage.setItem('loggedCustomer', JSON.stringify(customer));
    } else {
        localStorage.removeItem('loggedCustomer');
    }
}

/**
 * Déconnecte l'utilisateur
 */
export function logout() {
    setLoggedCustomer(null);
}
