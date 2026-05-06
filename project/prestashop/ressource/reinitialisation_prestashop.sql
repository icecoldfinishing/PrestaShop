-- Reinitialisation des donnees non essentielles (PrestaShop)
-- Conserve: lang, currency, country, tax, configuration, shop, employees, profils, modules, hooks.
-- Categories conservees: racines (is_root_category=1) et accueil (ps_shop.id_category).

START TRANSACTION;
SET FOREIGN_KEY_CHECKS = 0;

-- Commandes
DELETE FROM ps_order_detail_tax;
DELETE FROM ps_order_detail;
DELETE FROM ps_order_invoice_tax;
DELETE FROM ps_order_invoice_payment;
DELETE FROM ps_order_invoice;
DELETE FROM ps_order_return_detail;
DELETE FROM ps_order_return;
DELETE FROM ps_order_slip_detail;
DELETE FROM ps_order_slip;
DELETE FROM ps_order_payment;
DELETE FROM ps_order_carrier;
DELETE FROM ps_order_cart_rule;
DELETE FROM ps_order_history;
DELETE FROM ps_orders;

-- Paniers et regles panier
DELETE FROM ps_cart_cart_rule;
DELETE FROM ps_cart_product;
DELETE FROM ps_cart_rule_carrier;
DELETE FROM ps_cart_rule_combination;
DELETE FROM ps_cart_rule_country;
DELETE FROM ps_cart_rule_group;
DELETE FROM ps_cart_rule_lang;
DELETE FROM ps_cart_rule_product_rule_value;
DELETE FROM ps_cart_rule_product_rule_group;
DELETE FROM ps_cart_rule_product_rule;
DELETE FROM ps_cart_rule_shop;
DELETE FROM ps_cart_rule;
DELETE FROM ps_cart;

-- Clients, adresses, messages
DELETE FROM ps_customer_message;
DELETE FROM ps_customer_message_sync_imap;
DELETE FROM ps_customer_thread;
DELETE FROM ps_message_readed;
DELETE FROM ps_message;
DELETE FROM ps_customer_session;
DELETE FROM ps_emailsubscription;
DELETE FROM ps_mailalert_customer_oos;
DELETE FROM ps_guest;
DELETE FROM ps_address;
DELETE FROM ps_customer_group;
DELETE FROM ps_customer;

-- Personnalisation
DELETE FROM ps_customized_data;
DELETE FROM ps_customization;
DELETE FROM ps_customization_field_lang;
DELETE FROM ps_customization_field;

-- Wishlist
DELETE FROM ps_wishlist_product_cart;
DELETE FROM ps_wishlist_product;
DELETE FROM ps_wishlist;

-- Commentaires produits
DELETE FROM ps_product_comment_usefulness;
DELETE FROM ps_product_comment_report;
DELETE FROM ps_product_comment_grade;
DELETE FROM ps_product_comment_criterion_product;
DELETE FROM ps_product_comment_criterion_lang;
DELETE FROM ps_product_comment_criterion_category;
DELETE FROM ps_product_comment_criterion;
DELETE FROM ps_product_comment;

-- Catalogue: produits, images, stock, prix
DELETE FROM ps_product_attribute_combination;
DELETE FROM ps_product_attribute_image;
DELETE FROM ps_product_attribute_lang;
DELETE FROM ps_product_attribute_shop;
DELETE FROM ps_product_attribute;
DELETE FROM ps_product_attachment;
DELETE FROM ps_attachment_lang;
DELETE FROM ps_attachment;
DELETE FROM ps_product_carrier;
DELETE FROM ps_product_country_tax;
DELETE FROM ps_product_download;
DELETE FROM ps_product_group_reduction_cache;
DELETE FROM ps_product_lang;
DELETE FROM ps_product_sale;
DELETE FROM ps_product_shop;
DELETE FROM ps_product_supplier;
DELETE FROM ps_product_tag;
DELETE FROM ps_pack;
DELETE FROM ps_accessory;
DELETE FROM ps_specific_price_priority;
DELETE FROM ps_specific_price_rule_condition;
DELETE FROM ps_specific_price_rule_condition_group;
DELETE FROM ps_specific_price_rule;
DELETE FROM ps_specific_price;
DELETE FROM ps_stock_mvt;
DELETE FROM ps_stock;
DELETE FROM ps_stock_available;
DELETE FROM ps_image_lang;
DELETE FROM ps_image_shop;
DELETE FROM ps_image;
DELETE FROM ps_product;

-- Tags
DELETE FROM ps_tag_count;
DELETE FROM ps_tag;

-- Attributs et caracteristiques
DELETE FROM ps_feature_product;
DELETE FROM ps_feature_value_lang;
DELETE FROM ps_feature_value;
DELETE FROM ps_feature_lang;
DELETE FROM ps_feature_shop;
DELETE FROM ps_feature;
DELETE FROM ps_attribute_lang;
DELETE FROM ps_attribute_shop;
DELETE FROM ps_attribute;
DELETE FROM ps_attribute_group_lang;
DELETE FROM ps_attribute_group_shop;
DELETE FROM ps_attribute_group;

-- Categories (garder racines + accueil)
DELETE FROM ps_category_product;
DELETE FROM ps_category_group
	WHERE id_category NOT IN (SELECT id_category FROM ps_shop)
		AND id_category NOT IN (SELECT id_category FROM ps_category WHERE is_root_category = 1);
DELETE FROM ps_category_lang
	WHERE id_category NOT IN (SELECT id_category FROM ps_shop)
		AND id_category NOT IN (SELECT id_category FROM ps_category WHERE is_root_category = 1);
DELETE FROM ps_category_shop
	WHERE id_category NOT IN (SELECT id_category FROM ps_shop)
		AND id_category NOT IN (SELECT id_category FROM ps_category WHERE is_root_category = 1);
DELETE FROM ps_category
	WHERE is_root_category = 0
		AND id_category NOT IN (SELECT id_category FROM ps_shop);

-- Fabricants et fournisseurs
DELETE FROM ps_manufacturer_lang;
DELETE FROM ps_manufacturer_shop;
DELETE FROM ps_manufacturer;
DELETE FROM ps_supplier_lang;
DELETE FROM ps_supplier_shop;
DELETE FROM ps_supplier;

-- Achats/entrepots
DELETE FROM ps_supply_order_receipt_history;
DELETE FROM ps_supply_order_history;
DELETE FROM ps_supply_order_detail;
DELETE FROM ps_supply_order;
DELETE FROM ps_warehouse_product_location;
DELETE FROM ps_warehouse_carrier;
DELETE FROM ps_warehouse_shop;
DELETE FROM ps_warehouse;

-- CMS et contenu
DELETE FROM ps_cms_lang;
DELETE FROM ps_cms_shop;
DELETE FROM ps_cms;
DELETE FROM ps_cms_category_lang;
DELETE FROM ps_cms_category_shop;
DELETE FROM ps_cms_category;
DELETE FROM ps_linksmenutop_lang;
DELETE FROM ps_linksmenutop;
DELETE FROM ps_link_block_lang;
DELETE FROM ps_link_block_shop;
DELETE FROM ps_link_block;
DELETE FROM ps_homeslider_slides_lang;
DELETE FROM ps_homeslider_slides;
DELETE FROM ps_homeslider;
DELETE FROM ps_info_lang;
DELETE FROM ps_info_shop;
DELETE FROM ps_info;
DELETE FROM ps_psreassurance_lang;
DELETE FROM ps_psreassurance;

-- Layered navigation, recherche, SEO
DELETE FROM ps_layered_price_index;
DELETE FROM ps_layered_product_attribute;
DELETE FROM ps_layered_indexable_feature_value_lang_value;
DELETE FROM ps_layered_indexable_feature_lang_value;
DELETE FROM ps_layered_indexable_feature;
DELETE FROM ps_layered_indexable_attribute_lang_value;
DELETE FROM ps_layered_indexable_attribute_group_lang_value;
DELETE FROM ps_layered_indexable_attribute_group;
DELETE FROM ps_layered_filter_block;
DELETE FROM ps_layered_filter_shop;
DELETE FROM ps_layered_filter;
DELETE FROM ps_layered_category;
DELETE FROM ps_search_index;
DELETE FROM ps_search_word;
DELETE FROM ps_statssearch;
DELETE FROM ps_pagenotfound;
DELETE FROM ps_page_viewed;
DELETE FROM ps_gsitemap_sitemap;
DELETE FROM ps_fb_category_match;

-- Logs et statistiques
DELETE FROM ps_connections_page;
DELETE FROM ps_connections_source;
DELETE FROM ps_connections;
DELETE FROM ps_log;
DELETE FROM ps_admin_filter;
DELETE FROM ps_mail;
DELETE FROM ps_request_sql;
DELETE FROM ps_blockwishlist_statistics;
DELETE FROM ps_ganalytics_data;
DELETE FROM ps_ganalytics;
DELETE FROM ps_eventbus_incremental_sync;
DELETE FROM ps_eventbus_job;
DELETE FROM ps_eventbus_live_sync;
DELETE FROM ps_eventbus_type_sync;
DELETE FROM ps_smarty_cache;
DELETE FROM ps_smarty_lazy_cache;
DELETE FROM ps_smarty_last_flush;
DELETE FROM ps_advice_lang;
DELETE FROM ps_advice;
DELETE FROM ps_tab_advice;

-- Modules checkout / GDPR
DELETE FROM ps_pscheckout_tracking;
DELETE FROM ps_pscheckout_refund;
DELETE FROM ps_pscheckout_purchase_unit;
DELETE FROM ps_pscheckout_payment_token;
DELETE FROM ps_pscheckout_order_matrice;
DELETE FROM ps_pscheckout_order;
DELETE FROM ps_pscheckout_funding_source;
DELETE FROM ps_pscheckout_customer;
DELETE FROM ps_pscheckout_cart;
DELETE FROM ps_pscheckout_capture;
DELETE FROM ps_pscheckout_authorization;
DELETE FROM ps_psgdpr_log;
DELETE FROM ps_psgdpr_consent_lang;
DELETE FROM ps_psgdpr_consent;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
