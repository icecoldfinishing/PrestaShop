
# Configuration des modules

## Vue d'ensemble

La configuration des modules est le **cœur** du système de réinitialisation et d'import. Elle est centralisée dans un fichier unique : `src/config/reset-modules.config.ts`.

## Principe : Source Unique de Vérité

Toute la logique de suppression, d'import et d'affichage UI est **pilotée** par la configuration. Aucune table ou ressource n'est codée en dur dans les services ou composants. Pour ajouter un nouveau module, il suffit d'ajouter une entrée dans la configuration.

## Structure d'un module

```typescript
interface ResetModuleConfig {
  id: string;              // Identifiant unique (ex: 'customers')
  label: string;           // Libellé affiché dans l'UI
  description: string;     // Description courte
  icon: string;            // Classe Bootstrap Icon
  defaultChecked: boolean; // Coché par défaut dans l'UI

  apiResources: [          // Ressources API (ordonnées pour la suppression)
    {
      apiResource: string; // Nom de la ressource API (ex: 'customers')
      table: string;       // Table SQL correspondante (ex: 'ps_customer')
      singularTag: string; // Tag XML singulier (ex: 'customer')
    }
  ];

  cascadeTables: [         // Tables nettoyées automatiquement
    {
      table: string;       // Nom de la table (ex: 'ps_connections')
      cascadeParent: string; // Ressource parent (ex: 'customers')
    }
  ];

  dependsOn: string[];     // Modules qui doivent être traités avant
}
```

## Configuration actuelle

### Modules définis

| Module | ID | Ressources API | Tables cascade | Dépendances |
|--------|----|---------------|---------------|-------------|
| Customers | `customers` | 2 | 4 | aucune |
| Orders | `orders` | 6 | 6 | aucune |
| Products | `products` | 2 | 6 | aucune |

### Ordre d'exécution global

Défini dans `MODULE_EXECUTION_ORDER` :
```
['orders', 'customers', 'products']
```

Les commandes sont traitées en premier car elles référencent des clients et des produits.

## Ajouter un nouveau module

### Étape 1 : Définir la configuration

Dans `src/config/reset-modules.config.ts` :

```typescript
const NEW_MODULE: ResetModuleConfig = {
  id: 'my_module',
  label: 'My Module',
  description: 'Description du module',
  icon: 'bi-gear-fill',
  defaultChecked: true,
  apiResources: [
    { apiResource: 'my_resources', table: 'ps_my_table', singularTag: 'my_resource' },
  ],
  cascadeTables: [
    { table: 'ps_my_related_table', cascadeParent: 'my_resources' },
  ],
  dependsOn: [],
};
```

### Étape 2 : Ajouter au registre

```typescript
export const RESET_MODULES: ResetModuleConfigMap = {
  customers: CUSTOMER_MODULE,
  orders: ORDER_MODULE,
  products: PRODUCT_MODULE,
  my_module: NEW_MODULE,  // ← ajouter ici
};
```

### Étape 3 : Mettre à jour l'ordre d'exécution

```typescript
export const MODULE_EXECUTION_ORDER: string[] = [
  'orders', 'customers', 'products', 'my_module'
];
```

### Étape 4 (optionnel) : Lier à l'import

Dans `src/composables/useCSVImport.ts`, ajouter le mapping :

```typescript
const MODULE_RESOURCE_MAP: Record<string, string[]> = {
  customers: ['customers'],
  orders: ['orders'],
  products: ['products', 'categories', 'brands', 'suppliers'],
  my_module: ['my_resources'],  // ← ajouter ici
};
```

**C'est tout.** L'UI, le service de reset et l'import prendront automatiquement en compte le nouveau module.

## Fonctions utilitaires

| Fonction | Description |
|----------|-------------|
| `getExecutionOrder(ids)` | Filtre `MODULE_EXECUTION_ORDER` pour les IDs sélectionnés |
| `getAllTablesForModule(id)` | Retourne toutes les tables (API + cascade) d'un module |

## Choix techniques

### Pourquoi un seul fichier de configuration ?
Pour éviter la duplication et garantir la cohérence entre le reset, l'import et l'UI. Un seul endroit à modifier = moins de risques d'erreur.

### Pourquoi séparer API resources et cascade tables ?
Les ressources API sont supprimées activement via des appels DELETE. Les tables cascade sont nettoyées automatiquement par la base de données et n'ont pas d'endpoint API. Les distinguer permet un affichage clair dans l'UI et une documentation précise.

### Pourquoi un ordre d'exécution global ?
L'ordre inter-modules gère les dépendances de clés étrangères entre modules (ex: supprimer les commandes avant les clients). L'ordre intra-module (dans `apiResources`) gère les dépendances au sein d'un même module.
