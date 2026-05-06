# Documentation technique approfondie – PrestaShop 8.2.6

## 1. Architecture générale

PrestaShop est organisé en 4 couches :

- Présentation (Front + Back)
- Contrôleurs (flux HTTP)
- Logique métier (classes + services)
- Données (MySQL)

Deux systèmes coexistent :
- Legacy (ObjectModel + Smarty)
- Moderne (Symfony + Twig + Services)

---

# 2. MODULE PRODUIT

## 2.1 Fichiers principaux

### Legacy
- /classes/Product.php
- /classes/StockAvailable.php
- /classes/SpecificPrice.php
- /controllers/admin/AdminProductsController.php

### Symfony
- /src/Core/Product/
- /src/Adapter/Product/
- /src/PrestaShopBundle/Controller/Admin/ProductController.php

### Front
- /themes/*/templates/catalog/product.tpl

---

## 2.2 Modèle métier

Un produit est une entité centrale composée de :

- identité (id_product)
- prix
- stock
- déclinaisons
- catégories
- images
- règles de prix

Tables :
- ps_product
- ps_product_lang
- ps_stock_available
- ps_category_product

---

## 2.3 Workflow métier

### Création produit

1. Admin envoie formulaire
2. AdminProductsController reçoit la requête
3. Validation des champs
4. Création objet Product
5. Sauvegarde en base (ObjectModel)
6. Synchronisation stock
7. Génération index search
8. Vidage cache

---

### Lecture produit (front)

1. URL produit appelée
2. ProductController front
3. Chargement Product::getProduct()
4. Récupération translations
5. Calcul prix final
6. Application règles prix (SpecificPrice)
7. Envoi à Smarty

---

## 2.4 Règles métier

- un produit doit avoir au moins un nom
- prix ≥ 0
- stock synchronisé avec déclinaisons
- produit doit appartenir à une catégorie
- prix final = prix + taxes - promotions

---

# 3. MODULE CLIENT

## 3.1 Fichiers

- /classes/Customer.php
- /classes/Address.php
- /controllers/front/CustomerController.php
- /src/Core/Customer/

---

## 3.2 Modèle métier

Un client possède :

- identité
- email unique
- mot de passe hashé
- adresses multiples
- historique commandes

Tables :
- ps_customer
- ps_address

---

## 3.3 Workflow

### Inscription

1. Formulaire client soumis
2. Validation email unique
3. Hash password
4. Création Customer
5. Enregistrement DB
6. Envoi email confirmation

---

### Connexion

1. Email + password
2. Vérification hash
3. Création session
4. Token client

---

## 3.4 Règles métier

- email unique obligatoire
- password hash bcrypt
- client inactif non autorisé à commander

---

# 4. MODULE PANIER

## 4.1 Fichiers

- /classes/Cart.php
- /classes/CartRule.php
- /controllers/front/CartController.php

---

## 4.2 Modèle métier

Un panier contient :

- client ou session
- produits
- quantités
- prix calculé

Tables :
- ps_cart
- ps_cart_product

---

## 4.3 Workflow

1. Client ajoute produit
2. Cart::add()
3. Vérification stock
4. Mise à jour ligne panier
5. Recalcul total

---

## 4.4 Règles métier

- panier lié à session ou client
- stock doit être disponible
- suppression si inactif

---

# 5. MODULE COMMANDE

## 5.1 Fichiers

- /classes/Order.php
- /classes/OrderDetail.php
- /classes/OrderState.php
- /controllers/front/OrderController.php

---

## 5.2 Modèle métier

Une commande contient :

- client
- panier transformé
- produits
- paiement
- statut

Tables :
- ps_orders
- ps_order_detail
- ps_order_history

---

## 5.3 Workflow complet

### Étape 1 : validation panier
- Cart validé
- vérification stock

### Étape 2 : création commande
- Order::create()
- duplication panier

### Étape 3 : paiement
- module paiement appelé
- validation transaction

### Étape 4 : confirmation
- changement statut
- email client
- facture générée

---

## 5.4 Règles métier

- commande ne peut pas être vide
- stock réservé à validation
- statut obligatoire

---

# 6. MODULE CATEGORIE

## 6.1 Fichiers

- /classes/Category.php
- /controllers/admin/AdminCategoriesController.php

---

## 6.2 Modèle métier

- structure arborescente
- relation produit multiple

Tables :
- ps_category
- ps_category_product

---

## 6.3 Workflow

1. création catégorie
2. définition parent
3. positionnement arbre
4. indexation produits

---

# 7. MODULE PAIEMENT

## 7.1 Fichiers

- /modules/*
- /src/Core/Checkout/

---

## 7.2 Workflow

1. commande validée
2. appel module paiement
3. redirection PSP / Stripe
4. retour callback
5. validation commande

---

## 7.3 Règles

- paiement doit être validé avant confirmation
- callback sécurisé

---

# 8. MODULE LIVRAISON

## 8.1 Fichiers

- /classes/Carrier.php
- /classes/Delivery.php

---

## 8.2 Workflow

1. calcul poids panier
2. zone client
3. sélection transporteur
4. calcul prix

---

## 8.3 Règles

- transporteur dépend zone
- prix dépend poids / distance

---

# 9. MODULE WEB SERVICE API

## 9.1 Fichiers

- /classes/webservice/WebserviceRequest.php
- /classes/webservice/WebserviceOutputBuilder.php

---

## 9.2 Workflow API

1. requête HTTP
2. authentification clé API
3. routing resource (/api/products)
4. accès ObjectModel
5. sérialisation XML/JSON

---

## 9.3 Règles

- accès basé sur permissions
- XML par défaut
- structure stricte obligatoire

---

# 10. FRONT OFFICE

## 10.1 Fichiers

- /themes/
- product.tpl
- category.tpl
- cart.tpl

---

## 10.2 Workflow

1. requête URL
2. controller front
3. récupération données
4. passage Smarty
5. rendu HTML

---

# 11. BACK OFFICE

## 11.1 Fichiers

- /controllers/admin/
- /src/PrestaShopBundle/
- /Resources/views/

---

## 11.2 Workflow

1. requête admin
2. routing Symfony ou legacy
3. récupération services
4. Twig rendering
5. réponse HTML

---

# 12. CONCLUSION

PrestaShop 8.2.6 repose sur une architecture hybride :

- Legacy : logique métier principale
- Symfony : back-office moderne et services
- Modules : extensibilité
- API : accès externe

Le système est basé sur des workflows métier stricts autour des entités Product, Customer et Order.