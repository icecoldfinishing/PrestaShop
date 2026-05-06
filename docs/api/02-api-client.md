# Documentation : Client API PrestaShop

## Configuration

### Variables d'environnement (`.env`)

```env
VITE_PRESTASHOP_API_BASE_URL=/prestashop/api
VITE_PRESTASHOP_API_KEY=BY1KZWEGTP4PT968U85SR92T1EB5C8XQ
```

Le proxy Vite (`vite.config.ts`) redirige `/prestashop` vers `http://localhost:8088` pour éviter les problèmes CORS en développement :

```typescript
server: {
  proxy: {
    "/prestashop": {
      target: "http://localhost:8088",
      changeOrigin: true,
      rewrite: (path) => path.replace(/^\/prestashop/, "")
    }
  }
}
```

---

## Fichier : `src/lib/prestashop/client.ts`

### Classe `PrestashopClient`

Responsable de tous les appels HTTP vers le Webservice PrestaShop.

#### Authentification

PrestaShop Webservice utilise **HTTP Basic Auth** avec la clé API comme nom d'utilisateur et un mot de passe vide :

```typescript
Authorization: `Basic ${btoa(`${this.apiKey}:`)}`
// Exemple : "Basic QlkxS1pXRUdUUDRQVDk2OFU4NVNSOTJUMUVCNUMzWFE6"
```

Tous les appels forcent également le format XML :
```typescript
Accept: "application/xml"
"Output-Format": "XML"
```

---

### Méthode `getResource(resourceName, query?)` — Liste des ressources

**Utilisation :**
```typescript
const list = await prestashopClient.getResource("products", {
  display: "minimum",   // ne récupère que les IDs (plus léger)
  limit: "0,12"         // pagination : depuis l'offset 0, 12 éléments
});
```

**URL construite :**
```
GET /api/products?display=minimum&limit=0,12
```

**Réponse :**
```typescript
{
  rawXml: string,        // XML brut
  parsed: XmlObject,     // objet JS parsé
  resourceName: string   // "products"
}
```

---

### Méthode `getResourceById(resourceName, id, query?)` — Détail d'une ressource

**Utilisation :**
```typescript
const detail = await prestashopClient.getResourceById("products", 1, {
  display: "full"  // récupère tous les champs
});
```

**URL construite :**
```
GET /api/products/1?display=full
```

---

### Méthode `getImageUrl(resourceName, id, imageId)` — URL d'une image

Les images PrestaShop nécessitent la clé API dans l'URL (pas de header Auth supporté pour les images) :

```typescript
getImageUrl("products", 1, 14)
// → "/prestashop/api/images/products/1/14?ws_key=BY1KZWEGTP4PT968U85SR92T1EB5C8XQ"
```

---

## Fichier : `src/lib/prestashop/frontOfficeApi.ts`

### Interfaces TypeScript

```typescript
interface ProductItem {
  id: number;
  name: string;
  price: string;
  reference: string;
  shortDescription: string;
  imageId: number | null;
  imageUrl: string | null;
}

interface ProductDetail extends ProductItem {
  description: string;
  active: boolean;
}
```

---

### Fonction `fetchProductList(page, limit)` — Liste paginée

**Logique en 2 étapes :**

**Étape 1** — Récupère la liste des IDs :
```typescript
// Appel léger : display=minimum = seulement les IDs
GET /api/products?display=minimum&limit=OFFSET,LIMIT
```

**Étape 2** — Récupère le détail de chaque produit en parallèle :
```typescript
// Promise.all pour paralléliser les requêtes
GET /api/products/1?display=full
GET /api/products/2?display=full
...
```

**Pagination :**
```typescript
const offset = (page - 1) * limit;
limit: `${offset},${limit}`
// Page 1 → limit=0,12
// Page 2 → limit=12,12
// Page 3 → limit=24,12
```

---

### Fonction `mapProduct(product: XmlObject)` — Mapping XML → TypeScript

```typescript
function mapProduct(product: XmlObject): ProductDetail {
  const id        = getFieldNumber(product, "id") ?? 0;
  const name      = getFieldText(product, "name") || `Produit #${id}`;
  const reference = getFieldText(product, "reference");
  const price     = getFieldText(product, "price");
  const active    = getFieldText(product, "active") === "1";

  // Image : récupère l'ID de l'image par défaut et construit l'URL
  const imageId  = getFieldNumber(product, "id_default_image") ?? null;
  const imageUrl = imageId
    ? prestashopClient.getImageUrl("products", id, imageId)
    : null;
  ...
}
```

Le champ `id_default_image` dans PrestaShop est un attribut XML :
```xml
<id_default_image xlink:href="..." id="14"/>
```

Il est parsé comme `{ _attributes: { id: 14 } }`, et `getFieldNumber` gère ce cas via `valueExtractors.ts`.

---

### Fonction `findDetailNode(resourceName, parsed)` — Navigation dans le XML parsé

Navigue dans la structure `parsed.prestashop.product` (singulier) après avoir parsé la réponse d'un appel de détail :

```typescript
// Structure retournée par /api/products/1
{
  prestashop: {
    product: {   // ← findDetailNode retourne ce noeud
      id: 1,
      name: { ... },
      ...
    }
  }
}
```

---

## Hooks React : `src/hooks/useFrontOfficeData.ts`

### `useProducts(page, limit)` — Hook liste paginée

```typescript
const { data, loading, error } = useProducts(1, 12);
// data    → ProductItem[]
// loading → boolean
// error   → string | null
```

- Relance automatiquement le fetch quand `page` ou `limit` change
- Gère l'annulation du fetch si le composant est démonté (`cancelled` flag)

### `useProductDetail(productId)` — Hook détail produit

```typescript
const { data, loading, error } = useProductDetail(5);
// data → ProductDetail | null
```

- Ne fait rien si `productId` est `null`
- Lance le fetch dès que `productId` est un nombre valide
