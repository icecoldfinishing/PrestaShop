
# Système de réinitialisation des données (Data Reset)

## Vue d'ensemble

Le système de réinitialisation permet de supprimer sélectivement les données de la boutique PrestaShop, organisées par **modules**. Chaque module regroupe un ensemble de tables liées qui sont supprimées ensemble de manière cohérente.

## Architecture

```
src/config/reset-modules.config.ts    ← Configuration centralisée (source unique)
src/types/reset.types.ts              ← Définitions TypeScript
src/services/ResetModule.service.ts   ← Service de suppression via API
src/composables/useDataReset.ts       ← Composable Vue (état réactif)
src/components/reset/                 ← Composants UI
  ├── DataResetManager.vue            ← Page principale
  ├── ModuleSelector.vue              ← Sélection des modules
  ├── ResetProgress.vue               ← Progression en temps réel
  └── ResetReport.vue                 ← Rapport final
```

## Modules de réinitialisation

### 1. Customer Module
Supprime les données clients et adresses.

| Table | Méthode | Type |
|-------|---------|------|
| `ps_address` | API DELETE `/api/addresses/{id}` | API directe |
| `ps_customer` | API DELETE `/api/customers/{id}` | API directe |
| `ps_connections` | Cascade via customer | Automatique |
| `ps_connections_page` | Cascade via connections | Automatique |
| `ps_connections_source` | Cascade via connections | Automatique |
| `ps_guest` | Cascade via customer | Automatique |

### 2. Order Module
Supprime les commandes, factures, paiements et paniers.

| Table | Méthode | Type |
|-------|---------|------|
| `ps_order_detail` | API DELETE `/api/order_details/{id}` | API directe |
| `ps_order_history` | API DELETE `/api/order_histories/{id}` | API directe |
| `ps_order_payment` | API DELETE `/api/order_payments/{id}` | API directe |
| `ps_order_slip` | API DELETE `/api/order_slip/{id}` | API directe |
| `ps_orders` | API DELETE `/api/orders/{id}` | API directe |
| `ps_cart` | API DELETE `/api/carts/{id}` | API directe |
| `ps_order_invoice` | Cascade via orders | Automatique |
| `ps_order_invoice_payment` | Cascade via orders | Automatique |
| `ps_order_return` | Cascade via orders | Automatique |
| `ps_order_return_detail` | Cascade via orders | Automatique |
| `ps_order_slip_detail` | Cascade via order_slip | Automatique |
| `ps_cart_product` | Cascade via carts | Automatique |

### 3. Product Module
Supprime les produits, images et stocks.

| Table | Méthode | Type |
|-------|---------|------|
| `ps_stock_available` | API DELETE `/api/stock_availables/{id}` | API directe |
| `ps_product` | API DELETE `/api/products/{id}` | API directe |
| `ps_product_lang` | Cascade via products | Automatique |
| `ps_product_shop` | Cascade via products | Automatique |
| `ps_image` | Cascade via products | Automatique |
| `ps_image_lang` | Cascade via products | Automatique |
| `ps_image_shop` | Cascade via products | Automatique |
| `ps_category_product` | Cascade via products | Automatique |

## Ordre de suppression

### Ordre intra-module
Au sein de chaque module, les ressources enfants (dependantes) sont supprimées **avant** les parents pour éviter les erreurs de clés étrangères.

Exemple pour le module Orders :
```
order_details → order_histories → order_payments → order_slip → orders → carts
```

### Ordre inter-modules
Quand plusieurs modules sont sélectionnés, l'ordre global est :
1. **Orders** (car les commandes référencent customers et products)
2. **Customers**
3. **Products**

Cet ordre est défini dans `MODULE_EXECUTION_ORDER` dans `reset-modules.config.ts`.

## Flux d'exécution

```
1. L'utilisateur sélectionne les modules (checkboxes)
2. L'utilisateur clique "Réinitialiser"
3. Une modale de confirmation s'affiche
4. Après confirmation :
   a. Phase COUNTING :
      - Pour chaque module (dans l'ordre d'exécution) :
        - Pour chaque ressource API :
          - GET /api/{resource}?display=[id] → comptage des éléments
   b. Phase RUNNING :
      - Pour chaque module :
        - Pour chaque ressource API :
          - Pour chaque ID récupéré :
            - DELETE /api/{resource}/{id}
            - Mise à jour de la progression
   c. Phase COMPLETED :
      - Génération du rapport final
      - Affichage du résumé et des erreurs
```

## Gestion des erreurs

Le système adopte une stratégie **continue-on-error** :
- Si la suppression d'un élément échoue, l'erreur est enregistrée mais le processus continue
- Les erreurs sont affichées dans le rapport final avec le détail (module, ressource, ID, message)
- Le rapport peut être exporté en JSON pour analyse

## Contrôles

L'utilisateur peut :
- **Pause** : Interrompre temporairement le processus
- **Resume** : Reprendre après une pause
- **Cancel** : Annuler le processus (les suppressions déjà effectuées ne sont pas annulées)

## Fichiers clés

| Fichier | Rôle |
|---------|------|
| `src/config/reset-modules.config.ts` | Source unique de la configuration des modules |
| `src/types/reset.types.ts` | Interfaces TypeScript pour le reset |
| `src/services/ResetModule.service.ts` | Logique de suppression via API XML |
| `src/composables/useDataReset.ts` | État réactif Vue et actions |
| `src/components/reset/DataResetManager.vue` | Page UI principale |
| `src/components/reset/ModuleSelector.vue` | Sélecteur de modules avec checkboxes |
| `src/components/reset/ResetProgress.vue` | Barre de progression et détails |
| `src/components/reset/ResetReport.vue` | Rapport final avec erreurs |
