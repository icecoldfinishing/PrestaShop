# Système de Connexion Client (PrestaShop XML API)

Ce document explique comment le système de connexion pour les clients a été implémenté dans l'application.

## 1. Gestion de l'État Global (`src/utils/auth-state.js`)

Pour partager l'état de connexion entre les composants sans complexité inutile (comme Pinia), nous utilisons un état réactif simple basé sur les `ref` de Vue 3.

- **`loggedCustomer`** : Une `ref` qui contient l'objet client ou `null`.
- **`isLoggedIn`** : Un `computed` pour vérifier rapidement si un utilisateur est connecté.
- **`localStorage`** : L'état est persisté dans le navigateur pour rester connecté après un rafraîchissement.

## 2. API XML PrestaShop (`src/utils/prestashop-api.js`)

Une fonction `psLoginCustomer(email, password)` a été ajoutée. Elle fonctionne comme suit :
1. Recherche le client par email via `filter[email]`.
2. Récupère les détails complets (incluant le champ `passwd`).
3. Compare le mot de passe saisi avec celui stocké en utilisant **`bcryptjs`**.
   - *Note : PrestaShop 1.7+ utilise bcrypt. Le système détecte le format du hash et valide le texte brut saisi par l'utilisateur.*
4. Retourne un objet simplifié contenant les infos essentielles (id, email, nom, prénom).

## 3. Interface Utilisateur (`src/App.vue`)

- **Bouton de Connexion** : Ajouté dans la barre latérale. Si l'utilisateur n'est pas connecté, il voit un bouton "Se connecter" qui redirige vers le composant `auth.vue`.
- **Informations Client** : Si connecté, le nom et l'email du client s'affichent avec un bouton "Déconnexion".

## 4. Restriction d'Action (`src/components/product/ProductList.vue`)

- Un bouton **"Acheter"** a été ajouté à chaque produit.
- **Logique de redirection** : 
  - Si l'utilisateur clique sur "Acheter" sans être connecté, une alerte s'affiche et il est redirigé vers la page de connexion.
  - S'il est connecté, l'action est autorisée (simulation d'ajout au panier).

## 5. Comment tester

1. Allez dans la liste des produits.
2. Cliquez sur "Acheter" sur n'importe quel produit.
3. Vous devriez être redirigé vers la page de login.
4. Connectez-vous avec un email client existant dans votre boutique PrestaShop (le mot de passe doit correspondre au hash `passwd` pour cette démo, ou être identique si vous avez forcé des données de test).
5. Une fois connecté, vous verrez votre nom dans la barre latérale et pourrez cliquer sur "Acheter" sans être redirigé.
