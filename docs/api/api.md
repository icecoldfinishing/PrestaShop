# Documentation API PrestaShop 8.2.6 (XML)

## 1. Introduction

PrestaShop propose une API Webservice REST permettant d’accéder aux données de la boutique (produits, clients, commandes, etc.).

Le format principal utilisé est **XML**.  
Chaque ressource est accessible via une URL spécifique et manipulable avec les méthodes HTTP standards.

---

## 2. Configuration du Webservice

### Activation

Dans le back-office :

Paramètres avancés → Webservice

- Activer le webservice : OUI

---

### Création de clé API

- Cliquer sur "Ajouter"
- Générer une clé
- Donner les permissions (GET, POST, PUT, DELETE)

---

## 3. Authentification

Type : Basic Auth

- Username = clé API
- Password = vide

---

## 4. URL de base

http://localhost:8088/api/

---

## 5. Structure générale XML

Toutes les réponses suivent ce format :

```xml
<prestashop>
    <resource>
        ...
    </resource>
</prestashop>


Resource of type "p" does not exists. Did you mean: "tags"? The full list is: "addresses", "attachments", "carriers", "cart_rules", "carts", "categories", "combinations", "configurations", "contacts", "content_management_system", "countries", "currencies", "customer_messages", "customer_threads", "customers", "customizations", "deliveries", "employees", "groups", "guests", "image_types", "images", "languages", "manufacturers", "messages", "order_carriers", "order_cart_rules", "order_details", "order_histories", "order_invoices", "order_payments", "order_slip", "order_states", "orders", "price_ranges", "product_customization_fields", "product_feature_values", "product_features", "product_option_values", "product_options", "product_suppliers", "products", "search", "shop_groups", "shop_urls", "shops", "specific_price_rules", "specific_prices", "states", "stock_availables", "stock_movement_reasons", "stock_movements", "stocks", "stores", "suppliers", "supply_order_details", "supply_order_histories", "supply_order_receipt_histories", "supply_order_states", "supply_orders", "tags", "tax_rule_groups", "tax_rules", "taxes", "translated_configurations", "warehouse_product_locations", "warehouses", "weight_ranges", "zones"