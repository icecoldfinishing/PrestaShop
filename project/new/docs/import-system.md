
# Système d'import CSV dynamique

## Vue d'ensemble

Le module d'import permet d'importer des données depuis des fichiers CSV vers PrestaShop via l'API XML. L'import est entièrement configurable en front-end : parsing, mapping, types, validation et batch.

## Améliorations récentes

### Support des modules
L'import est désormais **module-aware** : les ressources disponibles sont filtrées en fonction des modules sélectionnés (customers, orders, products). Cela garantit la cohérence entre le système de reset et l'import.

### Related tables
Quand on importe un module, les tables liées sont automatiquement prises en compte. Par exemple, importer des "customers" gère aussi les adresses associées.

## Flux d'utilisation

1. Charger un ou plusieurs fichiers CSV
2. Sélectionner la ressource cible (produits, clients, commandes, etc.)
3. Configurer les paramètres de parsing et de mapping
4. Prévisualiser et corriger les lignes si besoin
5. Lancer l'import par batch

## Paramètres configurables

| Paramètre | Par défaut | Description |
|-----------|-----------|-------------|
| Séparateur CSV | `;` | Caractère de séparation des colonnes |
| Séparateur listes | `;` | Séparateur pour les valeurs multiples |
| Encodage | `UTF-8` | Encodage du fichier |
| Ligne d'en-tête | Oui | Première ligne comme en-tête |
| Format date | `dd/mm/yyyy` | Format de parsing des dates |
| Séparateur décimal | `,` | Séparateur des décimales |
| Séparateur milliers | espace | Séparateur des milliers |
| Taille batch | `20` | Nombre d'éléments par lot |

## Mapping dynamique

Le mapping se fait colonne par colonne :
- **Champ API** : chemin XML (ex: `product/name/language@value`)
- **Type** : string / integer / number / boolean / date
- **Required** : champ obligatoire
- **Default** : valeur par défaut si cellule vide

Le bouton "Utiliser mapping par défaut" charge les mappings et types préconfigurés pour la ressource sélectionnée depuis `resources.mapping.ts`.

## Filtrage par module

La propriété `moduleFilteredResourceOptions` filtre les ressources d'import selon les modules sélectionnés :

| Module | Ressources d'import |
|--------|-------------------|
| Customers | `customers` |
| Orders | `orders` |
| Products | `products`, `categories`, `brands`, `suppliers` |

Le mapping est défini dans `MODULE_RESOURCE_MAP` dans `useCSVImport.ts`.

## Validation

- Vérification des colonnes requises (en-têtes manquantes)
- Validation des champs obligatoires par ligne
- Validation des types (erreurs ligne par ligne)
- Conversion automatique : nombres, booléens, dates

## Batch import

- Taille de batch configurable par fichier
- Import pause/reprendre/annuler avec progression
- Envoi séquentiel des lignes via l'API XML

## Gestion des erreurs et reporting

- Erreurs en temps réel dans la prévisualisation
- Rapport détaillé en fin d'import (succès, erreurs, durée)
- Export JSON/CSV des résultats

## Fichiers clés

| Fichier | Rôle |
|---------|------|
| `src/components/import/CSVImportWizard.vue` | Orchestration de l'interface |
| `src/components/import/ImportSettingsPanel.vue` | UI des paramètres et mapping |
| `src/components/import/PreviewTable.vue` | Édition inline des données |
| `src/composables/useCSVImport.ts` | Logique principale (settings, validation, import, modules) |
| `src/services/CSVReader.service.ts` | Parsing CSV configurable |
| `src/services/ImportBatch.service.ts` | Envoi par batch via API |
| `src/services/XMLBuilder.service.ts` | Construction XML à partir du mapping |
| `src/config/resources.mapping.ts` | Mappings par ressource |
| `src/config/reset-modules.config.ts` | Configuration des modules (partagée avec le reset) |

## Notes techniques

- Le re-parsing d'un fichier réinitialise les lignes et edits
- Le mapping vide bloque l'import (message "Missing column mapping")
- L'auto-détection de la ressource fonctionne via le nom de fichier (ex: `products_import.csv`)
- Le filtrage par module est non-destructif : désélectionner un module ne supprime pas les fichiers déjà chargés
