<script setup lang="ts">
import { computed } from 'vue';
import { cart, psCreateCart, psCreateOrder, psEnsureCustomerAddress, psGetCustomerSecureKey } from '../../../utils/prestashop-api';
import { loggedCustomer } from '../../../utils/auth-state';

// Émissions pour la navigation
const emit = defineEmits(['continueShopping', 'orderSuccess']);

// Calcul du total avec taxes (si pas déjà fait dans le store)
const totalItems = computed(() => cart.count);
const totalPrice = computed(() => cart.total);

// Fonctions d'action
const removeItem = (cartId: string) => {
    cart.remove(cartId);
};

const updateQuantity = (cartId: string, value: number) => {
    cart.setQuantity(cartId, value);
};

const clearCart = () => {
    if (confirm("Voulez-vous vider votre panier ?")) {
        cart.clear();
    }
};

// Simulation de commande PrestaShop
const handleCheckout = async () => {
    if (!loggedCustomer.value) {
        alert("Vous devez être connecté pour commander.");
        return;
    }

    try {
        // On prépare les données pour l'API PrestaShop
        const itemsForApi = cart.items.map(item => ({
            id: item.id,
            quantity: item.quantity,
            id_attribute: 0 // Idéalement à récupérer via les variants
        }));

        const addressId = await psEnsureCustomerAddress(loggedCustomer.value);
        if (!addressId) {
            alert("Adresse client manquante.");
            return;
        }

        const secureKey = await psGetCustomerSecureKey(loggedCustomer.value.id);
        const cartId = await psCreateCart(loggedCustomer.value.id, itemsForApi, addressId);

        if (!cartId) {
            throw new Error("Cart ID introuvable.");
        }

        await psCreateOrder({
            cartId,
            customerId: loggedCustomer.value.id,
            addressId,
            total: totalPrice.value,
            secureKey,
        });

        alert("Commande validée dans PrestaShop !");
        cart.clear();
        emit('orderSuccess');
    } catch (error) {
        console.error("Erreur commande:", error);
        alert("Une erreur est survenue lors de la création du panier.");
    }
};
</script>

<template>
    <div class="cart-container container mt-4">
        <h2 class="mb-4">🛒 Votre Panier</h2>

        <div v-if="cart.items.length === 0" class="empty-cart text-center p-5 bg-white rounded shadow-sm">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <p class="mt-3 fs-4">Votre panier est tristement vide...</p>
            <button class="btn btn-primary mt-3" @click="emit('continueShopping')">
                Découvrir nos produits
            </button>
        </div>

        <div v-else class="row">
            <!-- Liste des produits -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="list-group list-group-flush">
                        <div v-for="item in cart.items" :key="item.cartId" class="list-group-item p-3">
                            <div class="row align-items-center">
                                <div class="col-2">
                                    <img :src="item.imageUrl || 'https://via.placeholder.com/100'"
                                        class="img-fluid rounded" alt="produit">
                                </div>
                                <div class="col-4">
                                    <h6 class="mb-0">{{ item.name }}</h6>
                                    <small class="text-muted" v-for="(val, label) in item.variants" :key="label">
                                        {{ label }}: {{ val }}
                                    </small>
                                </div>
                                <div class="col-2 text-center">
                                    <span class="fw-bold">{{ item.price.toFixed(2) }} €</span>
                                </div>
                                <div class="col-2">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Quantite</span>
                                        <input
                                            class="form-control"
                                            type="number"
                                            min="1"
                                            :value="item.quantity"
                                            @input="updateQuantity(item.cartId, Number($event.target.value))"
                                        />
                                    </div>
                                </div>
                                <div class="col-2 text-end">
                                    <button class="btn btn-outline-danger btn-sm" @click="removeItem(item.cartId)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-link text-danger p-0 mb-3" @click="clearCart">
                    <i class="bi bi-x-circle"></i> Vider le panier
                </button>
            </div>

            <!-- Résumé de la commande -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-primary">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Résumé</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Articles ({{ totalItems }})</span>
                            <span>{{ totalPrice.toFixed(2) }} €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Livraison</span>
                            <span class="text-success">Gratuite</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5">Total TTC</span>
                            <span class="h5 text-primary">{{ totalPrice.toFixed(2) }} €</span>
                        </div>

                        <button class="btn btn-success w-100 fw-bold py-2" @click="handleCheckout">
                            VALIDER LA COMMANDE
                        </button>

                        <button class="btn btn-outline-secondary w-100 mt-2 btn-sm" @click="emit('continueShopping')">
                            Continuer mes achats
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.cart-container {
    max-width: 1000px;
}

.card {
    border: none;
    border-radius: 12px;
}

.list-group-item {
    border-left: none;
    border-right: none;
}

.list-group-item:first-child {
    border-top: none;
}
</style>