
# Services API XML PrestaShop

## Vue d'ensemble

L'application communique avec PrestaShop via son **API XML Webservice**. Toutes les opérations (lecture, création, modification, suppression) passent par cette API REST qui accepte et retourne du XML.

## Configuration

### Variables d'environnement

```env
VITE_PRESTASHOP_API_KEY=Q2C19X4XAS6JFE2AHQCEMFZC7IY24LH5
VITE_PRESTASHOP_BASE_URL=/api
```

### Proxy Vite

Le proxy Vite redirige les appels `/api` vers le serveur PrestaShop :

```javascript
// vite.config.js
server: {
  proxy: {
    '/api': {
      target: 'http://localhost:8088',
      changeOrigin: true,
    }
  }
}
```

## Authentification

L'API utilise une clé d'authentification passée de deux manières :
1. **Query parameter** : `?ws_key=API_KEY` (méthode principale)
2. **Header Authorization** : `Basic base64(API_KEY:)` (méthode secondaire pour l'import)

## Endpoints utilisés

### Lecture (GET)

```
GET /api/{resource}                    → Liste tous les éléments
GET /api/{resource}/{id}               → Détail d'un élément
GET /api/{resource}?display=[id]       → Liste uniquement les IDs
GET /api/{resource}?display=full       → Liste avec tous les champs
```

Paramètres communs :
- `ws_key` : Clé API
- `output_format=XML` : Format de sortie

### Création (POST)

```
POST /api/{resource}
Content-Type: application/xml
Body: <prestashop><{item}>...</{item}></prestashop>
```

### Modification (PUT)

```
PUT /api/{resource}
Content-Type: application/xml
Body: <prestashop><{item}>...</{item}></prestashop>
```

### Suppression (DELETE)

```
DELETE /api/{resource}/{id}?ws_key=API_KEY
```

Retourne un status 200 en cas de succès, ou une erreur XML en cas d'échec.

## Ressources API utilisées

### Pour le reset

| Ressource API | Endpoint | Module |
|--------------|----------|--------|
| `customers` | `/api/customers` | Customers |
| `addresses` | `/api/addresses` | Customers |
| `orders` | `/api/orders` | Orders |
| `order_details` | `/api/order_details` | Orders |
| `order_histories` | `/api/order_histories` | Orders |
| `order_payments` | `/api/order_payments` | Orders |
| `order_slip` | `/api/order_slip` | Orders |
| `carts` | `/api/carts` | Orders |
| `products` | `/api/products` | Products |
| `stock_availables` | `/api/stock_availables` | Products |

### Pour l'import

| Ressource API | Endpoint | Méthode |
|--------------|----------|---------|
| `products` | `/api/products` | POST |
| `customers` | `/api/customers` | POST |
| `orders` | `/api/orders` | POST |
| `categories` | `/api/categories` | POST |
| `manufacturers` | `/api/manufacturers` | POST |
| `suppliers` | `/api/suppliers` | POST |

## Services et fichiers

### Couche basse : `src/utils/prestashop-api.js`

Fonctions utilitaires bas-niveau :

| Fonction | Description |
|----------|-------------|
| `psGet(resource, id, params)` | GET avec parsing XML |
| `psPost(resource, xmlData)` | POST de données XML |
| `psPut(resource, xmlData)` | PUT de données XML |
| `psDelete(resource, id)` | DELETE d'un élément |
| `psCount(resource)` | Compte le nombre d'éléments |
| `psGetAllIds(resource, singularTag)` | Liste tous les IDs d'une ressource |
| `getXmlText(value)` | Extrait le texte d'un nœud XML |

### Couche service : `src/services/prestashopApi.service.ts`

Version TypeScript avec typage fort. Utilisée principalement par les composants de vue.

### Service d'import : `src/services/ImportBatch.service.ts`

Gère l'envoi par batch avec :
- Construction XML via `XMLBuilder.service.ts`
- Authentification double (ws_key + Basic auth)
- Extraction de l'ID de réponse
- Parsing des erreurs PrestaShop
- Support pause/resume/cancel

### Service de reset : `src/services/ResetModule.service.ts`

Gère la suppression par module avec :
- Pré-comptage de tous les éléments (`psGetAllIds`)
- Suppression séquentielle (`psDelete`)
- Progression granulaire par ressource
- Gestion d'erreurs continue (ne s'arrête pas sur une erreur)
- Support pause/resume/cancel

## Format des réponses XML

### Liste d'éléments

```xml
<prestashop>
  <customers>
    <customer id="1" />
    <customer id="2" />
  </customers>
</prestashop>
```

### Détail d'un élément

```xml
<prestashop>
  <customer>
    <id>1</id>
    <firstname>John</firstname>
    <lastname>Doe</lastname>
  </customer>
</prestashop>
```

### Erreur

```xml
<prestashop>
  <errors>
    <error>
      <code>100</code>
      <message>The resource cannot be deleted</message>
    </error>
  </errors>
</prestashop>
```

## Parsing XML

La librairie `fast-xml-parser` est utilisée avec la configuration :

```typescript
const parser = new XMLParser({
  ignoreAttributes: false,
  attributeNamePrefix: '@_',
  parseTagValue: true,
  parseAttributeValue: true,
});
```

Les attributs XML sont préfixés par `@_` dans l'objet JS résultant (ex: `@_id`).

## Gestion des erreurs

Toutes les erreurs API sont normalisées :
1. Les erreurs Axios sont interceptées
2. Le body XML d'erreur est parsé si disponible
3. Le message d'erreur PrestaShop est extrait
4. Un message fallback est utilisé si le parsing échoue

## Limitations connues

- Certaines ressources PrestaShop ne supportent pas DELETE (ex: `order_invoices`)
- Les tables cascade n'ont pas d'endpoint API direct
- L'API limite parfois le nombre de requêtes simultanées
- Les images nécessitent un endpoint spécial (`/api/images/products/{id}`)
