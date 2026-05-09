# Système de Navigation (via Emit)

Ce document explique le système de navigation utilisé dans l'application, basé sur les **emits** et les **props** (sans Vue Router).

---

## Table des matières
- [Vue d'ensemble](#vue-densemble)
- [Principe de fonctionnement](#principe-de-fonctionnement)
- [Variables globales dans App.vue](#variables-globales-dans-appvue)
- [Événements disponibles](#événements-disponibles)
- [Exemples par composant](#exemples-par-composant)
- [Bonnes pratiques](#bonnes-pratiques)

---

## Vue d'ensemble

L'application utilise un système de navigation simple et centralisé :
- `App.vue` gère l'état global via `currentPage`
- Les composants enfants communiquent avec `App.vue` via des **emits**
- Les pages d'édition reçoivent l'`id` via **props**

---

## Principe de fonctionnement

1. `App.vue` contient une variable `currentPage` qui détermine quel composant afficher.
2. Les listes envoient un événement `edit` avec l'ID lorsqu'on clique sur "Edit".
3. `App.vue` change alors `currentPage` et passe l'ID via props au composant d'édition.
4. Les composants Edit et Create envoient `done` ou `cancel` pour revenir à la liste.

---

## Variables globales dans App.vue

| Variable                | Type     | Description |
|-------------------------|----------|-----------|
| `currentPage`           | String   | Page actuellement affichée |
| `selectedProductId`     | Number   | ID du produit en cours d'édition |
| `selectedCustomerId`    | Number   | ID du client en cours d'édition |

---

## Événements disponibles

### 1. Depuis les Listes vers App.vue

- **`@edit`** → Appelé quand on clique sur le bouton Edit
  - Payload : `id` (number)

**Exemple dans ProductList.vue ou CustomerList.vue :**
```vue
const emit = defineEmits<{ (e: 'edit', id: number): void }>();

const editProduct = (id: number) => {
    emit('edit', id);
};