# Documentation : Parser XML PrestaShop

## Principe général

PrestaShop Webservice retourne toujours du **XML brut**. L'application utilise l'API native du navigateur `DOMParser` pour convertir ce XML en un objet JavaScript (TypeScript) typé, sans aucune librairie externe.

---

## Fichier : `src/lib/xml/xmlParser.ts`

### Types de base

```typescript
// Valeur primitive possible dans le XML
export type XmlPrimitive = string | number | boolean | null;

// Une valeur peut être : primitive, objet, ou tableau de valeurs
export type XmlValue = XmlPrimitive | XmlObject | XmlValue[];

// Un noeud XML parsé = objet dont les clés sont les noms de tags
export interface XmlObject {
  [key: string]: XmlValue;
}
```

### Fonction principale : `parseXmlToObject(xml: string)`

```typescript
export function parseXmlToObject(xml: string): XmlObject
```

**Étapes :**
1. Utilise `new DOMParser().parseFromString(xml, "application/xml")`
2. Détecte les erreurs XML via `documentNode.querySelector("parsererror")`
3. Appelle `parseElement(root)` sur l'élément racine
4. Retourne `{ [root.tagName]: parseElement(root) }`

**Exemple :**

XML reçu de l'API :
```xml
<?xml version="1.0" encoding="UTF-8"?>
<prestashop xmlns:xlink="http://www.w3.org/1999/xlink">
  <product>
    <id>1</id>
    <name>
      <language id="1">T-shirt imprimé</language>
    </name>
    <price>23.900000</price>
    <active>1</active>
    <id_default_image xlink:href="http://..." id="1"/>
  </product>
</prestashop>
```

Objet JS résultant :
```json
{
  "prestashop": {
    "product": {
      "id": 1,
      "name": {
        "language": {
          "_attributes": { "id": 1 },
          "_text": "T-shirt imprimé"
        }
      },
      "price": 23.9,
      "active": 1,
      "id_default_image": {
        "_attributes": { "id": 1 }
      }
    }
  }
}
```

---

### Fonction interne : `parseElement(element: Element)`

Traite chaque noeud DOM récursivement :

| Cas | Résultat |
|---|---|
| Pas d'enfants, pas d'attributs | Valeur primitive normalisée (`normalizeText`) |
| Avec attributs | Objet avec clé `_attributes: {}` |
| Texte + attributs | Objet avec `_attributes` et `_text` |
| Plusieurs enfants | Objet dont les clés sont les noms de tags |
| Tag dupliqué | Tableau `[valeur1, valeur2, ...]` |

### Fonction interne : `normalizeText(input: string)`

Convertit le texte en type JS approprié :

```typescript
"" ou "  " → ""          // chaîne vide
"true"     → true         // booléen
"false"    → false        // booléen
"null"     → null         // null
"23.9"     → 23.9         // nombre
"T-shirt"  → "T-shirt"    // chaîne
```

---

## Fichier : `src/lib/prestashop/valueExtractors.ts`

Ce fichier fournit des fonctions d'aide pour extraire des valeurs depuis un `XmlObject` parsé.

### `getFieldText(source, key)` → `string`

Extrait le texte d'un champ, en gérant plusieurs cas de structure XML :

```typescript
// Cas 1 : valeur directe
product.price = 23.9  →  getFieldText(product, "price")  →  "23.9"

// Cas 2 : noeud avec _text
product.name = { _text: "T-shirt" }  →  "T-shirt"

// Cas 3 : noeud multilingue PrestaShop
product.name = { language: { _text: "T-shirt" } }  →  "T-shirt"
```

### `getFieldNumber(source, key)` → `number | null`

Convertit le résultat de `getFieldText` en nombre, ou `null` si invalide.

### `asObject(value)` → `XmlObject | null`

Vérifie et cast une `XmlValue` vers `XmlObject`. Retourne `null` si primitif ou tableau.

### `asArray(value)` → `XmlValue[]`

Force une valeur en tableau. Utile car un tag unique donne un objet, plusieurs tags donnent un tableau.

---

## Fichier : `src/lib/prestashop/resourceUtils.ts`

### `extractResourceIds(parsed, resourceName)` → `number[]`

Extrait la liste des IDs depuis une réponse de liste XML.

**Exemple de XML liste :**
```xml
<prestashop>
  <products>
    <product id="1" xlink:href="..."/>
    <product id="2" xlink:href="..."/>
    <product id="3" xlink:href="..."/>
  </products>
</prestashop>
```

**Logique :**
1. Accède à `parsed.prestashop[resourceName]` (ex: `parsed.prestashop.products`)
2. Parcourt tous les enfants de manière récursive (`collectIdsRecursively`)
3. Cherche `id` directement sur l'objet ou dans `_attributes.id`
4. Retourne un tableau de nombres uniques (via `Set<number>`)
