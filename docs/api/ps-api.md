# PrestaShop API Utils

Ce fichier contient les fonctions utilitaires pour interagir avec l'API Web Service de PrestaShop.

---

## Table des matières
- [Configuration](#configuration)
- [Fonctions disponibles](#fonctions-disponibles)
- [Utilisation](#utilisation)
- [Exemples](#exemples)
- [Bonnes pratiques](#bonnes-pratiques)

---

## Configuration

### Variables globales

| Variable     | Description                          | Valeur actuelle |
|--------------|--------------------------------------|-----------------|
| `API_KEY`    | Clé Web Service PrestaShop           | `EWWCJGTGEU8ZQFT5YTGEGQNTZLMJG688` |
| `BASE_URL`   | URL de base de l'API                 | `/api` |

### Dépendances

- `axios`
- `fast-xml-parser`

---

## Fonctions disponibles

### 1. `psGet(resource, id?, queryParams?)`

Récupère des données depuis PrestaShop.

**Paramètres :**
- `resource` (string) : Nom de la ressource (`customers`, `products`, `orders`, ...)
- `id` (string|number) : Identifiant optionnel
- `queryParams` (object) : Paramètres supplémentaires (`display`, `filter`, `sort`, etc.)

**Exemple :**
```ts
const data = await psGet('customers', '', { display: '[id,firstname,lastname,email]' });
const data = await psGet('products', 42, { display: 'full' });