# Architecture du parse XML generalise (React + TypeScript)

## Objectif

Construire une vraie application front-office React/TypeScript qui consomme les endpoints XML de PrestaShop (`/api/...`) sans logique specifique hard-codee par ressource.

Le principe est:

1. Appeler l'API Webservice PrestaShop en XML
2. Parser XML -> objet TypeScript generique
3. Extraire et normaliser les valeurs utiles
4. Mapper vers des modeles front-office (produits, clients, commandes, etc.)
5. Afficher dans des pages UI metier (catalogue, fiche produit, modules)

---

## Fichiers cles

### 1) Parseur XML generique

- `project/new/src/lib/xml/xmlParser.ts`

Role:

- Parse toute reponse XML via `DOMParser`
- Convertit recursivement en objet JS/TS
- Gere:
  - noeuds simples (`<id>1</id>`)
  - attributs (`<product id="1" .../>`) via `_attributes`
  - texte (`_text`)
  - noeuds repetes transformes en tableaux
- Retourne une structure racine:
  - `{ prestashop: ... }`

---

### 2) Client API PrestaShop (XML only)

- `project/new/src/lib/prestashop/client.ts`
- `project/new/src/lib/prestashop/types.ts`
- `project/new/src/lib/prestashop/index.ts`

Role:

- Centraliser l'authentification webservice (`Basic apiKey:`)
- Forcer des appels XML (`Accept: application/xml`)
- Fournir des fonctions generiques:
  - `getResource(resourceName, query)`
  - `getResourceById(resourceName, id, query)`
  - `getResourceListWithDetails(resourceName, options)`
- Supporter les query params dynamiques (`display`, `limit`, `filter[...]`, etc.)

---

### 3) Extraction d'IDs et valeurs depuis XML parse

- `project/new/src/lib/prestashop/resourceUtils.ts`
- `project/new/src/lib/prestashop/valueExtractors.ts`

Role:

- `resourceUtils.ts`:
  - Extrait les IDs depuis structures XML variees
  - Supporte `id` direct et `_attributes.id`
  - Parcourt recursivement les noeuds
- `valueExtractors.ts`:
  - `getText()` pour lire une valeur texte meme dans des structures imbriquees (`language`, `_text`, tableau)
  - `getNumber()` pour normaliser les numeriques
  - `getFieldText()` / `getFieldNumber()` pour usage metier simple

---

### 4) Couche metier front-office

- `project/new/src/lib/prestashop/frontOfficeApi.ts`

Role:

- Transformer les objets XML generiques en modeles utilisables par le front:
  - `ProductItem`
  - `ProductDetail`
- Implementer les use cases metier:
  - `fetchProductList()`
  - `fetchProductDetail()`
  - `fetchModuleRecords(resourceName)`

Important:

- Gestion de la singularisation de ressource pour s'adapter aux structures PrestaShop:
  - `products -> product`
  - `categories -> category`
  - etc.

---

### 5) Hooks React de chargement

- `project/new/src/hooks/useFrontOfficeData.ts`

Role:

- Encapsuler les appels async et etats UI:
  - `loading`
  - `error`
  - `data`
- Hooks exposes:
  - `useProducts()`
  - `useProductDetail()`
  - `useModuleRecords()`

---

### 6) UI front-office (pas XML brut)

- `project/new/src/pages/home/Home.tsx`
- `project/new/src/pages/products/ProductsPage.tsx`
- `project/new/src/pages/products/ProductDetailPage.tsx`
- `project/new/src/pages/modules/ModulesPage.tsx`
- `project/new/src/components/Layout.tsx`
- `project/new/src/routes/AppRouter.tsx`

Role:

- Afficher une application boutique:
  - accueil
  - catalogue produits
  - fiche detail produit
  - exploration multi-modules
- La couche UI ne manipule pas XML directement:
  - elle consomme des objets deja normalises via la couche metier

---

## Flux technique complet

1. UI appelle un hook (ex: `useProducts`)
2. Hook appelle `frontOfficeApi.ts`
3. `frontOfficeApi.ts` appelle `client.ts`
4. `client.ts` recupere XML depuis `/api/...`
5. `xmlParser.ts` convertit XML -> objet
6. `resourceUtils.ts` + `valueExtractors.ts` normalisent les donnees
7. `frontOfficeApi.ts` mappe vers un modele metier
8. UI affiche les donnees front-office

---

## Variables d'environnement

- `project/new/.env`
- `project/new/.env.example`

Variables:

- `VITE_PRESTASHOP_API_BASE_URL=/prestashop/api`
- `VITE_PRESTASHOP_API_KEY=...`

---

## Pourquoi cette approche est generalisee

- Meme parseur XML pour toutes les ressources PrestaShop
- Meme client HTTP/XML pour tous les endpoints
- Meme mecanisme list + detail pour tous les modules
- Mappers metier ajoutables par domaine sans modifier le parseur de base
- Facile d'etendre a:
  - customers
  - orders
  - categories
  - suppliers
  - autres ressources webservice

