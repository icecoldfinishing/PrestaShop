
# CSV Import dynamique (front-end)

## Objectif
Ce module permet un import CSV totalement configurable en front-end. Tous les parametres (parsing, mapping, types, validation, batch) sont geres dans l'UI, sans modification back-end.

## Flux d'utilisation
1. Charger un ou plusieurs fichiers CSV.
2. Selectionner la ressource (produits, clients, commandes, categories, etc.).
3. Configurer les parametres de parsing et de mapping.
4. Previsualiser et corriger les lignes si besoin.
5. Lancer l'import par batch.

## Parametres configurables (front-end)
- Separateur CSV (par defaut: ,)
- Separateur des listes/multiples (par defaut: ;)
- Encodage (par defaut: UTF-8)
- Presence d'une ligne d'entete (par defaut: oui)
- Format de date (par defaut: yyyy-mm-dd)
- Format des nombres: separateur decimal (par defaut: .) et milliers (par defaut: ,)
- Taille des batchs (par defaut: 20)

Tout changement de separateur, encodage ou entete relance le parsing du fichier.

## Mapping dynamique
Le mapping se fait colonne par colonne:
- Champ API: chemin XML (ex: product/name/language@value)
- Type: string / integer / number / boolean / date
- Required: champ obligatoire
- Default: valeur par defaut si cellule vide

Le bouton "Utiliser mapping par defaut" charge les mappings et types preconfigures pour la ressource selectionnee.

## Valeurs par defaut et conversions de type
- Si une cellule est vide et un default est defini, la valeur est injectee.
- Conversion automatique selon le type:
	- number / integer: respect du separateur decimal + milliers
	- boolean: 1/0, true/false, yes/no, y/n
	- date: format configure + gestion des dates Excel (serial)

## Validation
- Verification des colonnes requises (entetes manquantes)
- Validation des champs obligatoires par ligne
- Validation des types (erreurs ligne par ligne)

## Gestion des erreurs et reporting
- Erreurs en temps reel dans la preview
- Rapport detaille en fin d'import
- Export JSON/CSV des erreurs et succes

## Batch import
La taille de batch est configurable par fichier. L'import est pause/reprendre/annuler, avec progression.

## Fichiers clefs
- src/components/import/CSVImportWizard.vue: orchestration de l'interface
- src/components/import/ImportSettingsPanel.vue: UI des parametres et mapping
- src/components/import/PreviewTable.vue: edition inline
- src/composables/useCSVImport.ts: logique principale (settings, validation, import)
- src/services/CSVReader.service.ts: parsing CSV configurable
- src/services/ImportBatch.service.ts: envoi par batch
- src/services/XMLBuilder.service.ts: construction XML a partir du mapping
- src/config/resources.mapping.ts: exemples de mappings par ressource

## Notes
- Le re-parsing d'un fichier reinitialise les lignes et edits.
- Le mapping vide bloque l'import (message "Missing column mapping").
