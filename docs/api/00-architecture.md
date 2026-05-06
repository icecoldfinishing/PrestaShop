# Architecture du Front-Office PrestaShop (React + TypeScript)

## Vue d'ensemble

```
src/
├── lib/
│   ├── xml/
│   │   └── xmlParser.ts          # Parse XML brut → objet JS typé
│   └── prestashop/
│       ├── index.ts              # Instanciation du client singleton
│       ├── client.ts             # PrestashopClient : fetch + auth + parse
│       ├── types.ts              # Interfaces TypeScript de configuration
│       ├── resourceUtils.ts      # Extraction des IDs depuis le XML parsé
│       ├── valueExtractors.ts    # Extraction de texte/nombre depuis XmlObject
│       └── frontOfficeApi.ts     # API métier : produits, modules
├── hooks/
│   ├── useFrontOfficeData.ts     # Hooks React : useProducts, useProductDetail
│   └── usePrestashopDetailedList.ts
├── components/
│   ├── Layout.tsx                # Navbar + Footer + Outlet
│   ├── ResourceExplorer.tsx      # Explorateur de modules PrestaShop
│   └── XmlViewer.tsx             # Affichage tabulaire d'un XmlObject
├── pages/
│   ├── home/Home.tsx             # Page d'accueil + Hero + Produits en avant
│   ├── products/
│   │   ├── ProductsPage.tsx      # Catalogue paginé
│   │   └── ProductDetailPage.tsx # Fiche produit + panier
│   └── modules/ModulesPage.tsx   # Explorateur générique
└── routes/
    └── AppRouter.tsx             # Routage React Router v7
```

## Flux de données général

```
API PrestaShop XML
       |
       v
PrestashopClient.getResource() / getResourceById()
       |
       v  (réponse XML brute)
parseXmlToObject()   [xmlParser.ts]
       |
       v  (XmlObject typé)
extractResourceIds() / mapProduct() / getFieldText()
       |
       v  (données métier TypeScript : ProductItem, ProductDetail)
Hook React (useProducts / useProductDetail)
       |
       v  (state: data | loading | error)
Composant React (ProductsPage / ProductDetailPage)
```

## Technologies utilisées

| Couche | Outil |
|---|---|
| Framework UI | React 19 + TypeScript |
| Routing | React Router DOM v7 |
| Style | Bootstrap 5 |
| Build | Vite 8 |
| API | PrestaShop Webservice (XML uniquement) |
| Parse XML | DOMParser natif navigateur |

---

Voir les autres fichiers de documentation :
- [01-xml-parser.md](./01-xml-parser.md) — Comment fonctionne le parser XML
- [02-api-client.md](./02-api-client.md) — Comment sont faits les appels API
- [03-ui-affichage.md](./03-ui-affichage.md) — Comment est structuré l'affichage Bootstrap
