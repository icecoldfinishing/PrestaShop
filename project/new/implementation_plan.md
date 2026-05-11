# Goal Description

Implement an advanced "all-or-nothing" CSV import feature with support for importing all loaded files via a single button, handling image directories for product references, and custom CSV mappings to support specific datasets (Products, Customers, and Orders).

## Proposed Changes

### `src/components/BO/import/CSVImportWizard.vue`
- Add a new "Import All Files" button.
- Add an "Images Directory" input/upload zone to allow users to select a folder containing product reference images.
- Modify the UI to show global progress.

### `src/composables/useCSVImport.ts`
- Implement a `startImportAll` function that triggers an all-or-nothing transaction logic.
- Instead of committing directly, we can run validation on all files first. If any validation fails, stop.
- If the Prestashop API supports transactions or rollback, implement it, otherwise implement a soft rollback (delete imported entities if an error occurs later).
- Support storing the selected image directory files and linking them to products based on reference.

### `src/config/resources.mapping.ts`
- Update the mapping configuration for `products`, `customers`, and `orders` to match the custom CSV headers:
  - **Products**: `nom`, `reference`, `prix_ttc`, `Taxe`, `categorie`, `prix_achat`, `date_availability_produit`.
  - **Customers/Orders**: `date`, `nom`, `email`, `pwd`, `adresse`, `achat`, `etat`.

## User Review Required
> [!IMPORTANT]
> The PrestaShop XML API does not natively support atomic transactions across multiple API calls. An "all-or-nothing" rollback means we will need to track every successfully created ID and send `DELETE` requests for them if a subsequent row or file fails. Do you approve of this rollback strategy?
> Also, for `fichier3.csv`, a single row contains Customer and Order data (`nom`, `email`, `achat`, `etat`). The standard importer maps one CSV file to one PrestaShop resource. Do we need a custom parser to split this file into Customers and Orders, or will we modify the CSV before uploading?

## Open Questions
- For `fichier3.csv`, the `achat` field looks like `[("T_01";3;"ngoza")]` (Product Reference, Quantity, Size). How should this be parsed into Order Details?
- For `fichier1.csv`, `categorie` is a string (e.g. "Akanjo"). The Prestashop API usually expects an integer `id_category_default`. Should the importer automatically look up or create the category based on the string name?
- How should the images in `data/J1test/images` be uploaded? PrestaShop has a specific Images API `/api/images/products/{id}`. Is this the intended endpoint?

il faut aussi que tu gere par rapoort aux fichier le taux taxe si ye en a pas , tu dois creeer je crois selon logique , et gere bien les variants des produits comme dans le fichier csv 2