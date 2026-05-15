import { ref, computed } from 'vue';

// État réactif pour le client connecté
export const loggedCustomer = ref(null);

// Getters utiles
export const isLoggedIn = computed(() => !!loggedCustomer.value);

/**
 * Définit le client connecté et persiste dans localStorage
 * @param {Object|null} customer 
 */
export function setLoggedCustomer(customer) {
    loggedCustomer.value = customer;
}

/**
 * Déconnecte l'utilisateur
 */
export function logout() {
    setLoggedCustomer(null);
}

/** Session invitée FO (pas d’id client PrestaShop). */
export function enterFoGuest() {
    setLoggedCustomer({
        id: null,
        email: null,
        firstname: 'Invité',
        lastname: '',
        guest: true,
    });
}

export function isFoGuest() {
    return !!loggedCustomer.value?.guest;
}

export const loggedAdmin = ref(null);
export const isAdminLoggedIn = computed(() => !!loggedAdmin.value);

export function setLoggedAdmin(admin) {
    loggedAdmin.value = admin;
}

export function adminLogout() {
    setLoggedAdmin(null);
}