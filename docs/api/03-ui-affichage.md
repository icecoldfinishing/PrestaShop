# Documentation : Interface Utilisateur (Bootstrap 5)

## Structure globale

L'application est organisée autour d'un **Layout** partagé avec trois zones :

```
┌─────────────────────────────────────────────────┐
│  NAVBAR (sticky-top, bg-dark)                   │
│  Logo | Accueil | Produits | Modules | [Panier] │
├─────────────────────────────────────────────────┤
│                                                 │
│  <Outlet /> → contenu de la page active         │
│  (plein écran, px-4 py-4)                       │
│                                                 │
├─────────────────────────────────────────────────┤
│  FOOTER (bg-dark, texte copyright)              │
└─────────────────────────────────────────────────┘
```

---

## Fichier : `src/components/Layout.tsx`

### Points clés

- `d-flex flex-column min-vh-100` : le layout s'étend toujours sur toute la hauteur de l'écran
- Navbar `sticky-top` : reste visible lors du défilement
- `<main className="flex-grow-1 w-100 px-4 py-4">` : le contenu prend **toute la largeur** sans contrainte de `container` ni `max-width`
- Le lien actif dans la navbar est mis en valeur via `active fw-semibold` (NavLink React Router)
- Le footer est poussé en bas grâce à `mt-auto` et `flex-grow-1` sur le main

### Responsivité de la navbar

Bootstrap gère automatiquement le menu hamburger sur mobile via :
```tsx
data-bs-toggle="collapse"
data-bs-target="#navbarNav"
```
Le JS Bootstrap est importé dans `main.tsx` via :
```typescript
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
```

---

## Page : `src/pages/home/Home.tsx`

### Section Hero

Bannière pleine largeur avec fond de couleur primaire Bootstrap :

```tsx
<div className="py-5 mb-5 bg-primary text-white">
  <h1 className="display-5 fw-bold mb-3">...</h1>
  <p className="fs-5 mb-4">...</p>
  <div className="d-flex gap-3 flex-wrap">
    <Link className="btn btn-light btn-lg">Voir catalogue</Link>
    <Link className="btn btn-outline-light btn-lg">Voir modules</Link>
  </div>
</div>
```

- Pas de `container` ni de boîte blanche : la bannière est vraiment bord à bord
- `flex-wrap` sur les boutons pour passer à la ligne sur mobile

### Grille de produits mis en avant

```tsx
<div className="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
```

| Breakpoint | Colonnes affichées |
|---|---|
| `< 768px` (mobile) | 2 colonnes |
| `>= 768px` (tablette) | 3 colonnes |
| `>= 992px` (desktop) | 4 colonnes |
| `>= 1200px` (large) | 5 colonnes |

---

## Page : `src/pages/products/ProductsPage.tsx`

### Grille catalogue

Même logique responsive que la page d'accueil :

```tsx
<div className="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4 mb-5">
  {products.map((product) => (
    <div className="col" key={product.id}>
      <div className="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
        ...
      </div>
    </div>
  ))}
</div>
```

### Card produit (structure)

```
┌──────────────────────┐
│  Image (220px haut)  │  ← img ou placeholder "Pas d'image"
├──────────────────────┤
│  Référence (small)   │
│  Nom du produit      │  ← text-truncate si trop long
│  Description courte  │  ← 2 lignes max (WebkitLineClamp)
│                      │
│  Prix   [Voir détail]│  ← mt-auto pour coller en bas
└──────────────────────┘
```

### Nettoyage HTML des descriptions

Les descriptions PrestaShop contiennent souvent des balises HTML. On les supprime pour l'affichage dans les cards :

```typescript
product.shortDescription.replace(/(<([^>]+)>)/gi, "")
```

### Pagination

La pagination utilise la structure Bootstrap `pagination` avec des boutons `page-link` :

```
[Précédent]  [2]  [Suivant]
```

- **Précédent** est `disabled` quand `page === 1`
- **Suivant** est `disabled` quand `products.length < limit` (dernière page atteinte)
- Le changement de page déclenche un nouveau fetch via `useProducts(page, limit)`

### États de chargement et erreur

```tsx
// Chargement
<div className="spinner-border text-primary" role="status">
  <span className="visually-hidden">Chargement...</span>
</div>

// Erreur
<div className="alert alert-danger" role="alert">
  {error}
</div>
```

---

## Page : `src/pages/products/ProductDetailPage.tsx`

### Layout en 2 colonnes

```
┌───────────────────┬────────────────────────────────┐
│  Image produit    │  Nom du produit                 │
│  (col-md-5)       │  [Badge ref] [Badge stock]      │
│  fond bg-light    │  Prix en display-6              │
│  object-fit:      │                                 │
│  contain          │  [Ajouter au panier]            │
│                   │                                 │
│                   │  ── Description ──              │
│                   │  (HTML rendu via               │
│                   │   dangerouslySetInnerHTML)      │
└───────────────────┴────────────────────────────────┘
```

- Sur mobile les colonnes s'empilent verticalement (Bootstrap grid par défaut)
- `col-md-5` / `col-md-7` active le layout 2 colonnes à partir de 768px

### Bouton "Ajouter au panier" (simulation UI)

```typescript
const [addedToCart, setAddedToCart] = useState(false);

const handleAddToCart = () => {
  setAddedToCart(true);
  setTimeout(() => setAddedToCart(false), 3000); // alerte disparaît après 3s
};
```

- Le bouton est `disabled` si `product.active === false` (rupture de stock)
- L'alerte verte `alert-success` s'affiche pendant 3 secondes après le clic
- Aucun appel API réel n'est effectué (UI uniquement)

### Breadcrumb

```tsx
<nav aria-label="breadcrumb">
  <ol className="breadcrumb">
    <li className="breadcrumb-item"><Link to="/">Accueil</Link></li>
    <li className="breadcrumb-item"><Link to="/products">Catalogue</Link></li>
    <li className="breadcrumb-item active">{product.name}</li>
  </ol>
</nav>
```

### Affichage de la description HTML

Les descriptions PrestaShop sont en HTML riche. On les affiche directement avec `dangerouslySetInnerHTML` :

```tsx
<div dangerouslySetInnerHTML={{
  __html: product.description || product.shortDescription || "Aucune description."
}} />
```

> **Note :** `dangerouslySetInnerHTML` est acceptable ici car la source (PrestaShop) est votre propre back-office, pas une entrée utilisateur externe.

---

## Composant : `src/components/ResourceExplorer.tsx`

Permet d'explorer n'importe quelle ressource PrestaShop disponible via le Webservice.

### Modules disponibles

```typescript
const DEFAULT_MODULES = [
  "products", "customers", "orders",
  "categories", "manufacturers", "suppliers"
];
```

### Fonctionnement

1. L'utilisateur sélectionne un module dans le `<select>` Bootstrap (`form-select-lg`)
2. `usePrestashopDetailedList(resource, { listLimit: 10, detailLimit: 5 })` est appelé
3. Les données du premier élément sont affichées dans `XmlViewer`

---

## Composant : `src/components/XmlViewer.tsx`

Affiche un `XmlObject` sous forme de tableau Bootstrap avec aplatissement récursif.

### Aplatissement (`flattenObject`)

Convertit un objet imbriqué en liste de paires clé/valeur :

```typescript
// Entrée
{ product: { id: 1, name: { _text: "T-shirt" } } }

// Sortie
[
  { key: "product.id",         value: "1"       },
  { key: "product.name._text", value: "T-shirt" }
]
```

- Limité aux **25 premiers champs** pour éviter les tableaux trop longs
- Les clés sont affichées en `font-monospace` dans la colonne gauche
- Les valeurs sont dans des `badge bg-light` avec `word-break: break-all`
