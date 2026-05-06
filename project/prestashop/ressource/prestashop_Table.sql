-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 06 mai 2026 à 09:36
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `prestashop`
--

-- --------------------------------------------------------

--
-- Structure de la table `ps_access`
--

DROP TABLE IF EXISTS `ps_access`;
CREATE TABLE IF NOT EXISTS `ps_access` (
  `id_profile` int UNSIGNED NOT NULL,
  `id_authorization_role` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_profile`,`id_authorization_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_accessory`
--

DROP TABLE IF EXISTS `ps_accessory`;
CREATE TABLE IF NOT EXISTS `ps_accessory` (
  `id_product_1` int UNSIGNED NOT NULL,
  `id_product_2` int UNSIGNED NOT NULL,
  KEY `accessory_product` (`id_product_1`,`id_product_2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_address`
--

DROP TABLE IF EXISTS `ps_address`;
CREATE TABLE IF NOT EXISTS `ps_address` (
  `id_address` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_country` int UNSIGNED NOT NULL,
  `id_state` int UNSIGNED DEFAULT NULL,
  `id_customer` int UNSIGNED NOT NULL DEFAULT '0',
  `id_manufacturer` int UNSIGNED NOT NULL DEFAULT '0',
  `id_supplier` int UNSIGNED NOT NULL DEFAULT '0',
  `id_warehouse` int UNSIGNED NOT NULL DEFAULT '0',
  `alias` varchar(32) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `address1` varchar(128) NOT NULL,
  `address2` varchar(128) DEFAULT NULL,
  `postcode` varchar(12) DEFAULT NULL,
  `city` varchar(64) NOT NULL,
  `other` mediumtext,
  `phone` varchar(32) DEFAULT NULL,
  `phone_mobile` varchar(32) DEFAULT NULL,
  `vat_number` varchar(32) DEFAULT NULL,
  `dni` varchar(16) DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `active` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `deleted` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_address`),
  KEY `address_customer` (`id_customer`),
  KEY `id_country` (`id_country`),
  KEY `id_state` (`id_state`),
  KEY `id_manufacturer` (`id_manufacturer`),
  KEY `id_supplier` (`id_supplier`),
  KEY `id_warehouse` (`id_warehouse`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_address_format`
--

DROP TABLE IF EXISTS `ps_address_format`;
CREATE TABLE IF NOT EXISTS `ps_address_format` (
  `id_country` int UNSIGNED NOT NULL,
  `format` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_admin_filter`
--

DROP TABLE IF EXISTS `ps_admin_filter`;
CREATE TABLE IF NOT EXISTS `ps_admin_filter` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee` int NOT NULL,
  `shop` int NOT NULL,
  `controller` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `filter` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `filter_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_filter_search_id_idx` (`employee`,`shop`,`controller`,`action`,`filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_advice`
--

DROP TABLE IF EXISTS `ps_advice`;
CREATE TABLE IF NOT EXISTS `ps_advice` (
  `id_advice` int NOT NULL AUTO_INCREMENT,
  `id_ps_advice` int NOT NULL,
  `id_tab` int NOT NULL,
  `ids_tab` text,
  `validated` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `hide` tinyint(1) NOT NULL DEFAULT '0',
  `location` enum('after','before') NOT NULL,
  `selector` varchar(255) DEFAULT NULL,
  `start_day` int NOT NULL DEFAULT '0',
  `stop_day` int NOT NULL DEFAULT '0',
  `weight` int DEFAULT '1',
  PRIMARY KEY (`id_advice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_advice_lang`
--

DROP TABLE IF EXISTS `ps_advice_lang`;
CREATE TABLE IF NOT EXISTS `ps_advice_lang` (
  `id_advice` int NOT NULL,
  `id_lang` int NOT NULL,
  `html` text,
  PRIMARY KEY (`id_advice`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_alias`
--

DROP TABLE IF EXISTS `ps_alias`;
CREATE TABLE IF NOT EXISTS `ps_alias` (
  `id_alias` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `alias` varchar(191) NOT NULL,
  `search` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_alias`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_api_access`
--

DROP TABLE IF EXISTS `ps_api_access`;
CREATE TABLE IF NOT EXISTS `ps_api_access` (
  `id_api_access` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_authorized_application` int UNSIGNED NOT NULL,
  `client_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `scopes` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id_api_access`),
  KEY `IDX_6E064442D8BFF738` (`id_authorized_application`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_attachment`
--

DROP TABLE IF EXISTS `ps_attachment`;
CREATE TABLE IF NOT EXISTS `ps_attachment` (
  `id_attachment` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `file` varchar(40) NOT NULL,
  `file_name` varchar(128) NOT NULL,
  `file_size` bigint UNSIGNED NOT NULL DEFAULT '0',
  `mime` varchar(128) NOT NULL,
  PRIMARY KEY (`id_attachment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_attachment_lang`
--

DROP TABLE IF EXISTS `ps_attachment_lang`;
CREATE TABLE IF NOT EXISTS `ps_attachment_lang` (
  `id_attachment` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  `description` mediumtext,
  PRIMARY KEY (`id_attachment`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_attribute`
--

DROP TABLE IF EXISTS `ps_attribute`;
CREATE TABLE IF NOT EXISTS `ps_attribute` (
  `id_attribute` int NOT NULL AUTO_INCREMENT,
  `id_attribute_group` int NOT NULL,
  `color` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int NOT NULL,
  PRIMARY KEY (`id_attribute`),
  KEY `attribute_group` (`id_attribute_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_attribute_group`
--

DROP TABLE IF EXISTS `ps_attribute_group`;
CREATE TABLE IF NOT EXISTS `ps_attribute_group` (
  `id_attribute_group` int NOT NULL AUTO_INCREMENT,
  `is_color_group` tinyint(1) NOT NULL,
  `group_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int NOT NULL,
  PRIMARY KEY (`id_attribute_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_attribute_group_lang`
--

DROP TABLE IF EXISTS `ps_attribute_group_lang`;
CREATE TABLE IF NOT EXISTS `ps_attribute_group_lang` (
  `id_attribute_group` int NOT NULL,
  `id_lang` int NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `public_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_attribute_group`,`id_lang`),
  KEY `IDX_4653726C67A664FB` (`id_attribute_group`),
  KEY `IDX_4653726CBA299860` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_attribute_group_shop`
--

DROP TABLE IF EXISTS `ps_attribute_group_shop`;
CREATE TABLE IF NOT EXISTS `ps_attribute_group_shop` (
  `id_attribute_group` int NOT NULL,
  `id_shop` int NOT NULL,
  PRIMARY KEY (`id_attribute_group`,`id_shop`),
  KEY `IDX_DB30BAAC67A664FB` (`id_attribute_group`),
  KEY `IDX_DB30BAAC274A50A0` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_attribute_lang`
--

DROP TABLE IF EXISTS `ps_attribute_lang`;
CREATE TABLE IF NOT EXISTS `ps_attribute_lang` (
  `id_attribute` int NOT NULL,
  `id_lang` int NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_attribute`,`id_lang`),
  KEY `IDX_3ABE46A77A4F53DC` (`id_attribute`),
  KEY `IDX_3ABE46A7BA299860` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_attribute_shop`
--

DROP TABLE IF EXISTS `ps_attribute_shop`;
CREATE TABLE IF NOT EXISTS `ps_attribute_shop` (
  `id_attribute` int NOT NULL,
  `id_shop` int NOT NULL,
  PRIMARY KEY (`id_attribute`,`id_shop`),
  KEY `IDX_A7DD8E677A4F53DC` (`id_attribute`),
  KEY `IDX_A7DD8E67274A50A0` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_authorization_role`
--

DROP TABLE IF EXISTS `ps_authorization_role`;
CREATE TABLE IF NOT EXISTS `ps_authorization_role` (
  `id_authorization_role` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(191) NOT NULL,
  PRIMARY KEY (`id_authorization_role`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_authorized_application`
--

DROP TABLE IF EXISTS `ps_authorized_application`;
CREATE TABLE IF NOT EXISTS `ps_authorized_application` (
  `id_authorized_application` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_authorized_application`),
  UNIQUE KEY `UNIQ_475B9BA55E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_blockwishlist_statistics`
--

DROP TABLE IF EXISTS `ps_blockwishlist_statistics`;
CREATE TABLE IF NOT EXISTS `ps_blockwishlist_statistics` (
  `id_statistics` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_cart` int UNSIGNED DEFAULT NULL,
  `id_product` int UNSIGNED NOT NULL,
  `id_product_attribute` int UNSIGNED NOT NULL,
  `date_add` datetime NOT NULL,
  `id_shop` int UNSIGNED DEFAULT '1',
  PRIMARY KEY (`id_statistics`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_carrier`
--

DROP TABLE IF EXISTS `ps_carrier`;
CREATE TABLE IF NOT EXISTS `ps_carrier` (
  `id_carrier` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_reference` int UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `deleted` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `shipping_handling` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `range_behavior` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `is_module` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `is_free` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `shipping_external` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `need_range` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `external_module_name` varchar(64) DEFAULT NULL,
  `shipping_method` int NOT NULL DEFAULT '0',
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  `max_width` int DEFAULT '0',
  `max_height` int DEFAULT '0',
  `max_depth` int DEFAULT '0',
  `max_weight` decimal(20,6) DEFAULT '0.000000',
  `grade` int DEFAULT '0',
  PRIMARY KEY (`id_carrier`),
  KEY `deleted` (`deleted`,`active`),
  KEY `reference` (`id_reference`,`deleted`,`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_carrier_group`
--

DROP TABLE IF EXISTS `ps_carrier_group`;
CREATE TABLE IF NOT EXISTS `ps_carrier_group` (
  `id_carrier` int UNSIGNED NOT NULL,
  `id_group` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_carrier`,`id_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_carrier_lang`
--

DROP TABLE IF EXISTS `ps_carrier_lang`;
CREATE TABLE IF NOT EXISTS `ps_carrier_lang` (
  `id_carrier` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_lang` int UNSIGNED NOT NULL,
  `delay` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id_lang`,`id_shop`,`id_carrier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_carrier_shop`
--

DROP TABLE IF EXISTS `ps_carrier_shop`;
CREATE TABLE IF NOT EXISTS `ps_carrier_shop` (
  `id_carrier` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_carrier`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_carrier_tax_rules_group_shop`
--

DROP TABLE IF EXISTS `ps_carrier_tax_rules_group_shop`;
CREATE TABLE IF NOT EXISTS `ps_carrier_tax_rules_group_shop` (
  `id_carrier` int UNSIGNED NOT NULL,
  `id_tax_rules_group` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_carrier`,`id_tax_rules_group`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_carrier_zone`
--

DROP TABLE IF EXISTS `ps_carrier_zone`;
CREATE TABLE IF NOT EXISTS `ps_carrier_zone` (
  `id_carrier` int UNSIGNED NOT NULL,
  `id_zone` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_carrier`,`id_zone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart`
--

DROP TABLE IF EXISTS `ps_cart`;
CREATE TABLE IF NOT EXISTS `ps_cart` (
  `id_cart` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop_group` int UNSIGNED NOT NULL DEFAULT '1',
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_carrier` int UNSIGNED NOT NULL,
  `delivery_option` mediumtext NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `id_address_delivery` int UNSIGNED NOT NULL,
  `id_address_invoice` int UNSIGNED NOT NULL,
  `id_currency` int UNSIGNED NOT NULL,
  `id_customer` int UNSIGNED NOT NULL,
  `id_guest` int UNSIGNED NOT NULL,
  `secure_key` varchar(32) NOT NULL DEFAULT '-1',
  `recyclable` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `gift` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `gift_message` mediumtext,
  `mobile_theme` tinyint(1) NOT NULL DEFAULT '0',
  `allow_seperated_package` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `checkout_session_data` mediumtext,
  PRIMARY KEY (`id_cart`),
  KEY `cart_customer` (`id_customer`),
  KEY `id_address_delivery` (`id_address_delivery`),
  KEY `id_address_invoice` (`id_address_invoice`),
  KEY `id_carrier` (`id_carrier`),
  KEY `id_lang` (`id_lang`),
  KEY `id_currency` (`id_currency`),
  KEY `id_guest` (`id_guest`),
  KEY `id_shop_group` (`id_shop_group`),
  KEY `id_shop_2` (`id_shop`,`date_upd`),
  KEY `id_shop` (`id_shop`,`date_add`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart_cart_rule`
--

DROP TABLE IF EXISTS `ps_cart_cart_rule`;
CREATE TABLE IF NOT EXISTS `ps_cart_cart_rule` (
  `id_cart` int UNSIGNED NOT NULL,
  `id_cart_rule` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_cart`,`id_cart_rule`),
  KEY `id_cart_rule` (`id_cart_rule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart_product`
--

DROP TABLE IF EXISTS `ps_cart_product`;
CREATE TABLE IF NOT EXISTS `ps_cart_product` (
  `id_cart` int UNSIGNED NOT NULL,
  `id_product` int UNSIGNED NOT NULL,
  `id_address_delivery` int UNSIGNED NOT NULL DEFAULT '0',
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_product_attribute` int UNSIGNED NOT NULL DEFAULT '0',
  `id_customization` int UNSIGNED NOT NULL DEFAULT '0',
  `quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_cart`,`id_product`,`id_product_attribute`,`id_customization`,`id_address_delivery`),
  KEY `id_product_attribute` (`id_product_attribute`),
  KEY `id_cart_order` (`id_cart`,`date_add`,`id_product`,`id_product_attribute`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart_rule`
--

DROP TABLE IF EXISTS `ps_cart_rule`;
CREATE TABLE IF NOT EXISTS `ps_cart_rule` (
  `id_cart_rule` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_customer` int UNSIGNED NOT NULL DEFAULT '0',
  `date_from` datetime NOT NULL,
  `date_to` datetime NOT NULL,
  `description` mediumtext,
  `quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `quantity_per_user` int UNSIGNED NOT NULL DEFAULT '0',
  `priority` int UNSIGNED NOT NULL DEFAULT '1',
  `partial_use` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `code` varchar(254) NOT NULL,
  `minimum_amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `minimum_amount_tax` tinyint(1) NOT NULL DEFAULT '0',
  `minimum_amount_currency` int UNSIGNED NOT NULL DEFAULT '0',
  `minimum_amount_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `country_restriction` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `carrier_restriction` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `group_restriction` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `cart_rule_restriction` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `product_restriction` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `shop_restriction` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `free_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `reduction_percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `reduction_amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `reduction_tax` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `reduction_currency` int UNSIGNED NOT NULL DEFAULT '0',
  `reduction_product` int NOT NULL DEFAULT '0',
  `reduction_exclude_special` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `gift_product` int UNSIGNED NOT NULL DEFAULT '0',
  `gift_product_attribute` int UNSIGNED NOT NULL DEFAULT '0',
  `highlight` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_cart_rule`),
  KEY `id_customer` (`id_customer`,`active`,`date_to`),
  KEY `group_restriction` (`group_restriction`,`active`,`date_to`),
  KEY `id_customer_2` (`id_customer`,`active`,`highlight`,`date_to`),
  KEY `group_restriction_2` (`group_restriction`,`active`,`highlight`,`date_to`),
  KEY `date_from` (`date_from`),
  KEY `date_to` (`date_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart_rule_carrier`
--

DROP TABLE IF EXISTS `ps_cart_rule_carrier`;
CREATE TABLE IF NOT EXISTS `ps_cart_rule_carrier` (
  `id_cart_rule` int UNSIGNED NOT NULL,
  `id_carrier` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_cart_rule`,`id_carrier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart_rule_combination`
--

DROP TABLE IF EXISTS `ps_cart_rule_combination`;
CREATE TABLE IF NOT EXISTS `ps_cart_rule_combination` (
  `id_cart_rule_1` int UNSIGNED NOT NULL,
  `id_cart_rule_2` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_cart_rule_1`,`id_cart_rule_2`),
  KEY `id_cart_rule_1` (`id_cart_rule_1`),
  KEY `id_cart_rule_2` (`id_cart_rule_2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart_rule_country`
--

DROP TABLE IF EXISTS `ps_cart_rule_country`;
CREATE TABLE IF NOT EXISTS `ps_cart_rule_country` (
  `id_cart_rule` int UNSIGNED NOT NULL,
  `id_country` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_cart_rule`,`id_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart_rule_group`
--

DROP TABLE IF EXISTS `ps_cart_rule_group`;
CREATE TABLE IF NOT EXISTS `ps_cart_rule_group` (
  `id_cart_rule` int UNSIGNED NOT NULL,
  `id_group` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_cart_rule`,`id_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart_rule_lang`
--

DROP TABLE IF EXISTS `ps_cart_rule_lang`;
CREATE TABLE IF NOT EXISTS `ps_cart_rule_lang` (
  `id_cart_rule` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(254) NOT NULL,
  PRIMARY KEY (`id_cart_rule`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart_rule_product_rule`
--

DROP TABLE IF EXISTS `ps_cart_rule_product_rule`;
CREATE TABLE IF NOT EXISTS `ps_cart_rule_product_rule` (
  `id_product_rule` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product_rule_group` int UNSIGNED NOT NULL,
  `type` enum('products','categories','attributes','manufacturers','suppliers') NOT NULL,
  PRIMARY KEY (`id_product_rule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart_rule_product_rule_group`
--

DROP TABLE IF EXISTS `ps_cart_rule_product_rule_group`;
CREATE TABLE IF NOT EXISTS `ps_cart_rule_product_rule_group` (
  `id_product_rule_group` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_cart_rule` int UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_product_rule_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart_rule_product_rule_value`
--

DROP TABLE IF EXISTS `ps_cart_rule_product_rule_value`;
CREATE TABLE IF NOT EXISTS `ps_cart_rule_product_rule_value` (
  `id_product_rule` int UNSIGNED NOT NULL,
  `id_item` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_product_rule`,`id_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cart_rule_shop`
--

DROP TABLE IF EXISTS `ps_cart_rule_shop`;
CREATE TABLE IF NOT EXISTS `ps_cart_rule_shop` (
  `id_cart_rule` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_cart_rule`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_category`
--

DROP TABLE IF EXISTS `ps_category`;
CREATE TABLE IF NOT EXISTS `ps_category` (
  `id_category` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_parent` int UNSIGNED NOT NULL,
  `id_shop_default` int UNSIGNED NOT NULL DEFAULT '1',
  `level_depth` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `nleft` int UNSIGNED NOT NULL DEFAULT '0',
  `nright` int UNSIGNED NOT NULL DEFAULT '0',
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  `is_root_category` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_category`),
  KEY `category_parent` (`id_parent`),
  KEY `nleftrightactive` (`nleft`,`nright`,`active`),
  KEY `level_depth` (`level_depth`),
  KEY `nright` (`nright`),
  KEY `activenleft` (`active`,`nleft`),
  KEY `activenright` (`active`,`nright`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_category_group`
--

DROP TABLE IF EXISTS `ps_category_group`;
CREATE TABLE IF NOT EXISTS `ps_category_group` (
  `id_category` int UNSIGNED NOT NULL,
  `id_group` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_category`,`id_group`),
  KEY `id_category` (`id_category`),
  KEY `id_group` (`id_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_category_lang`
--

DROP TABLE IF EXISTS `ps_category_lang`;
CREATE TABLE IF NOT EXISTS `ps_category_lang` (
  `id_category` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `description` mediumtext,
  `additional_description` mediumtext,
  `link_rewrite` varchar(128) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id_category`,`id_shop`,`id_lang`),
  KEY `category_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_category_product`
--

DROP TABLE IF EXISTS `ps_category_product`;
CREATE TABLE IF NOT EXISTS `ps_category_product` (
  `id_category` int UNSIGNED NOT NULL,
  `id_product` int UNSIGNED NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_category`,`id_product`),
  KEY `id_product` (`id_product`),
  KEY `id_category` (`id_category`,`position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_category_shop`
--

DROP TABLE IF EXISTS `ps_category_shop`;
CREATE TABLE IF NOT EXISTS `ps_category_shop` (
  `id_category` int NOT NULL,
  `id_shop` int NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_category`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cms`
--

DROP TABLE IF EXISTS `ps_cms`;
CREATE TABLE IF NOT EXISTS `ps_cms` (
  `id_cms` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_cms_category` int UNSIGNED NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `indexation` tinyint UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_cms`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cms_category`
--

DROP TABLE IF EXISTS `ps_cms_category`;
CREATE TABLE IF NOT EXISTS `ps_cms_category` (
  `id_cms_category` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_parent` int UNSIGNED NOT NULL,
  `level_depth` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_cms_category`),
  KEY `category_parent` (`id_parent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cms_category_lang`
--

DROP TABLE IF EXISTS `ps_cms_category_lang`;
CREATE TABLE IF NOT EXISTS `ps_cms_category_lang` (
  `id_cms_category` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `name` varchar(128) NOT NULL,
  `description` mediumtext,
  `link_rewrite` varchar(128) NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id_cms_category`,`id_shop`,`id_lang`),
  KEY `category_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cms_category_shop`
--

DROP TABLE IF EXISTS `ps_cms_category_shop`;
CREATE TABLE IF NOT EXISTS `ps_cms_category_shop` (
  `id_cms_category` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_cms_category`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cms_lang`
--

DROP TABLE IF EXISTS `ps_cms_lang`;
CREATE TABLE IF NOT EXISTS `ps_cms_lang` (
  `id_cms` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `meta_title` varchar(255) NOT NULL,
  `head_seo_title` varchar(255) DEFAULT NULL,
  `meta_description` varchar(512) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `content` longtext,
  `link_rewrite` varchar(128) NOT NULL,
  PRIMARY KEY (`id_cms`,`id_shop`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cms_role`
--

DROP TABLE IF EXISTS `ps_cms_role`;
CREATE TABLE IF NOT EXISTS `ps_cms_role` (
  `id_cms_role` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `id_cms` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_cms_role`,`id_cms`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cms_role_lang`
--

DROP TABLE IF EXISTS `ps_cms_role_lang`;
CREATE TABLE IF NOT EXISTS `ps_cms_role_lang` (
  `id_cms_role` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_cms_role`,`id_lang`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_cms_shop`
--

DROP TABLE IF EXISTS `ps_cms_shop`;
CREATE TABLE IF NOT EXISTS `ps_cms_shop` (
  `id_cms` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_cms`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_condition`
--

DROP TABLE IF EXISTS `ps_condition`;
CREATE TABLE IF NOT EXISTS `ps_condition` (
  `id_condition` int NOT NULL AUTO_INCREMENT,
  `id_ps_condition` int NOT NULL,
  `type` enum('configuration','install','sql') NOT NULL,
  `request` text,
  `operator` varchar(32) DEFAULT NULL,
  `value` varchar(64) DEFAULT NULL,
  `result` varchar(64) DEFAULT NULL,
  `calculation_type` enum('hook','time') DEFAULT NULL,
  `calculation_detail` varchar(64) DEFAULT NULL,
  `validated` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_condition`,`id_ps_condition`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_condition_advice`
--

DROP TABLE IF EXISTS `ps_condition_advice`;
CREATE TABLE IF NOT EXISTS `ps_condition_advice` (
  `id_condition` int NOT NULL,
  `id_advice` int NOT NULL,
  `display` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_condition`,`id_advice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_configuration`
--

DROP TABLE IF EXISTS `ps_configuration`;
CREATE TABLE IF NOT EXISTS `ps_configuration` (
  `id_configuration` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop_group` int UNSIGNED DEFAULT NULL,
  `id_shop` int UNSIGNED DEFAULT NULL,
  `name` varchar(254) NOT NULL,
  `value` mediumtext,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_configuration`),
  KEY `name` (`name`),
  KEY `id_shop` (`id_shop`),
  KEY `id_shop_group` (`id_shop_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_configuration_kpi`
--

DROP TABLE IF EXISTS `ps_configuration_kpi`;
CREATE TABLE IF NOT EXISTS `ps_configuration_kpi` (
  `id_configuration_kpi` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop_group` int UNSIGNED DEFAULT NULL,
  `id_shop` int UNSIGNED DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `value` mediumtext,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_configuration_kpi`),
  KEY `name` (`name`),
  KEY `id_shop` (`id_shop`),
  KEY `id_shop_group` (`id_shop_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_configuration_kpi_lang`
--

DROP TABLE IF EXISTS `ps_configuration_kpi_lang`;
CREATE TABLE IF NOT EXISTS `ps_configuration_kpi_lang` (
  `id_configuration_kpi` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `value` mediumtext,
  `date_upd` datetime DEFAULT NULL,
  PRIMARY KEY (`id_configuration_kpi`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_configuration_lang`
--

DROP TABLE IF EXISTS `ps_configuration_lang`;
CREATE TABLE IF NOT EXISTS `ps_configuration_lang` (
  `id_configuration` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `value` mediumtext,
  `date_upd` datetime DEFAULT NULL,
  PRIMARY KEY (`id_configuration`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_connections`
--

DROP TABLE IF EXISTS `ps_connections`;
CREATE TABLE IF NOT EXISTS `ps_connections` (
  `id_connections` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop_group` int UNSIGNED NOT NULL DEFAULT '1',
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_guest` int UNSIGNED NOT NULL,
  `id_page` int UNSIGNED NOT NULL,
  `ip_address` bigint DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `http_referer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_connections`),
  KEY `id_guest` (`id_guest`),
  KEY `date_add` (`date_add`),
  KEY `id_page` (`id_page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_connections_page`
--

DROP TABLE IF EXISTS `ps_connections_page`;
CREATE TABLE IF NOT EXISTS `ps_connections_page` (
  `id_connections` int UNSIGNED NOT NULL,
  `id_page` int UNSIGNED NOT NULL,
  `time_start` datetime NOT NULL,
  `time_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id_connections`,`id_page`,`time_start`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_connections_source`
--

DROP TABLE IF EXISTS `ps_connections_source`;
CREATE TABLE IF NOT EXISTS `ps_connections_source` (
  `id_connections_source` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_connections` int UNSIGNED NOT NULL,
  `http_referer` varchar(255) DEFAULT NULL,
  `request_uri` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_connections_source`),
  KEY `connections` (`id_connections`),
  KEY `orderby` (`date_add`),
  KEY `http_referer` (`http_referer`),
  KEY `request_uri` (`request_uri`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_contact`
--

DROP TABLE IF EXISTS `ps_contact`;
CREATE TABLE IF NOT EXISTS `ps_contact` (
  `id_contact` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `customer_service` tinyint(1) NOT NULL DEFAULT '0',
  `position` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_contact`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_contact_lang`
--

DROP TABLE IF EXISTS `ps_contact_lang`;
CREATE TABLE IF NOT EXISTS `ps_contact_lang` (
  `id_contact` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` mediumtext,
  PRIMARY KEY (`id_contact`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_contact_shop`
--

DROP TABLE IF EXISTS `ps_contact_shop`;
CREATE TABLE IF NOT EXISTS `ps_contact_shop` (
  `id_contact` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_contact`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_country`
--

DROP TABLE IF EXISTS `ps_country`;
CREATE TABLE IF NOT EXISTS `ps_country` (
  `id_country` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_zone` int UNSIGNED NOT NULL,
  `id_currency` int UNSIGNED NOT NULL DEFAULT '0',
  `iso_code` varchar(3) NOT NULL,
  `call_prefix` int NOT NULL DEFAULT '0',
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `contains_states` tinyint(1) NOT NULL DEFAULT '0',
  `need_identification_number` tinyint(1) NOT NULL DEFAULT '0',
  `need_zip_code` tinyint(1) NOT NULL DEFAULT '1',
  `zip_code_format` varchar(12) NOT NULL DEFAULT '',
  `display_tax_label` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_country`),
  KEY `country_iso_code` (`iso_code`),
  KEY `country_` (`id_zone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_country_lang`
--

DROP TABLE IF EXISTS `ps_country_lang`;
CREATE TABLE IF NOT EXISTS `ps_country_lang` (
  `id_country` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id_country`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_country_shop`
--

DROP TABLE IF EXISTS `ps_country_shop`;
CREATE TABLE IF NOT EXISTS `ps_country_shop` (
  `id_country` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_country`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_currency`
--

DROP TABLE IF EXISTS `ps_currency`;
CREATE TABLE IF NOT EXISTS `ps_currency` (
  `id_currency` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `iso_code` varchar(3) NOT NULL DEFAULT '0',
  `numeric_iso_code` varchar(3) DEFAULT NULL,
  `precision` int NOT NULL DEFAULT '6',
  `conversion_rate` decimal(13,6) NOT NULL,
  `deleted` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `active` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `unofficial` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `modified` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_currency`),
  KEY `currency_iso_code` (`iso_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_currency_lang`
--

DROP TABLE IF EXISTS `ps_currency_lang`;
CREATE TABLE IF NOT EXISTS `ps_currency_lang` (
  `id_currency` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `pattern` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_currency`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_currency_shop`
--

DROP TABLE IF EXISTS `ps_currency_shop`;
CREATE TABLE IF NOT EXISTS `ps_currency_shop` (
  `id_currency` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  `conversion_rate` decimal(13,6) NOT NULL,
  PRIMARY KEY (`id_currency`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_customer`
--

DROP TABLE IF EXISTS `ps_customer`;
CREATE TABLE IF NOT EXISTS `ps_customer` (
  `id_customer` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop_group` int UNSIGNED NOT NULL DEFAULT '1',
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_gender` int UNSIGNED NOT NULL,
  `id_default_group` int UNSIGNED NOT NULL DEFAULT '1',
  `id_lang` int UNSIGNED DEFAULT NULL,
  `id_risk` int UNSIGNED NOT NULL DEFAULT '1',
  `company` varchar(255) DEFAULT NULL,
  `siret` varchar(14) DEFAULT NULL,
  `ape` varchar(6) DEFAULT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `last_passwd_gen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `birthday` date DEFAULT NULL,
  `newsletter` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `ip_registration_newsletter` varchar(15) DEFAULT NULL,
  `newsletter_date_add` datetime DEFAULT NULL,
  `optin` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `website` varchar(128) DEFAULT NULL,
  `outstanding_allow_amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `show_public_prices` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `max_payment_days` int UNSIGNED NOT NULL DEFAULT '60',
  `secure_key` varchar(32) NOT NULL DEFAULT '-1',
  `note` mediumtext,
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `is_guest` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `reset_password_token` varchar(40) DEFAULT NULL,
  `reset_password_validity` datetime DEFAULT NULL,
  PRIMARY KEY (`id_customer`),
  KEY `customer_email` (`email`),
  KEY `customer_login` (`email`,`passwd`),
  KEY `id_customer_passwd` (`id_customer`,`passwd`),
  KEY `id_gender` (`id_gender`),
  KEY `id_shop_group` (`id_shop_group`),
  KEY `id_shop` (`id_shop`,`date_add`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_customer_group`
--

DROP TABLE IF EXISTS `ps_customer_group`;
CREATE TABLE IF NOT EXISTS `ps_customer_group` (
  `id_customer` int UNSIGNED NOT NULL,
  `id_group` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_customer`,`id_group`),
  KEY `customer_login` (`id_group`),
  KEY `id_customer` (`id_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_customer_message`
--

DROP TABLE IF EXISTS `ps_customer_message`;
CREATE TABLE IF NOT EXISTS `ps_customer_message` (
  `id_customer_message` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_customer_thread` int DEFAULT NULL,
  `id_employee` int UNSIGNED DEFAULT NULL,
  `message` mediumtext NOT NULL,
  `file_name` varchar(18) DEFAULT NULL,
  `ip_address` varchar(16) DEFAULT NULL,
  `user_agent` varchar(128) DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `private` tinyint NOT NULL DEFAULT '0',
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_customer_message`),
  KEY `id_customer_thread` (`id_customer_thread`),
  KEY `id_employee` (`id_employee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_customer_message_sync_imap`
--

DROP TABLE IF EXISTS `ps_customer_message_sync_imap`;
CREATE TABLE IF NOT EXISTS `ps_customer_message_sync_imap` (
  `md5_header` varbinary(32) NOT NULL,
  KEY `md5_header_index` (`md5_header`(4))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_customer_session`
--

DROP TABLE IF EXISTS `ps_customer_session`;
CREATE TABLE IF NOT EXISTS `ps_customer_session` (
  `id_customer_session` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_customer` int UNSIGNED DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_customer_session`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_customer_thread`
--

DROP TABLE IF EXISTS `ps_customer_thread`;
CREATE TABLE IF NOT EXISTS `ps_customer_thread` (
  `id_customer_thread` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_lang` int UNSIGNED NOT NULL,
  `id_contact` int UNSIGNED NOT NULL,
  `id_customer` int UNSIGNED DEFAULT NULL,
  `id_order` int UNSIGNED DEFAULT NULL,
  `id_product` int UNSIGNED DEFAULT NULL,
  `status` enum('open','closed','pending1','pending2') NOT NULL DEFAULT 'open',
  `email` varchar(255) NOT NULL,
  `token` varchar(12) DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_customer_thread`),
  KEY `id_shop` (`id_shop`),
  KEY `id_lang` (`id_lang`),
  KEY `id_contact` (`id_contact`),
  KEY `id_customer` (`id_customer`),
  KEY `id_order` (`id_order`),
  KEY `id_product` (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_customization`
--

DROP TABLE IF EXISTS `ps_customization`;
CREATE TABLE IF NOT EXISTS `ps_customization` (
  `id_customization` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product_attribute` int UNSIGNED NOT NULL DEFAULT '0',
  `id_address_delivery` int UNSIGNED NOT NULL DEFAULT '0',
  `id_cart` int UNSIGNED NOT NULL,
  `id_product` int NOT NULL,
  `quantity` int NOT NULL,
  `quantity_refunded` int NOT NULL DEFAULT '0',
  `quantity_returned` int NOT NULL DEFAULT '0',
  `in_cart` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_customization`,`id_cart`,`id_product`,`id_address_delivery`),
  KEY `id_product_attribute` (`id_product_attribute`),
  KEY `id_cart_product` (`id_cart`,`id_product`,`id_product_attribute`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_customization_field`
--

DROP TABLE IF EXISTS `ps_customization_field`;
CREATE TABLE IF NOT EXISTS `ps_customization_field` (
  `id_customization_field` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product` int UNSIGNED NOT NULL,
  `type` tinyint(1) NOT NULL,
  `required` tinyint(1) NOT NULL,
  `is_module` tinyint(1) NOT NULL DEFAULT '0',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_customization_field`),
  KEY `id_product` (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_customization_field_lang`
--

DROP TABLE IF EXISTS `ps_customization_field_lang`;
CREATE TABLE IF NOT EXISTS `ps_customization_field_lang` (
  `id_customization_field` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id_customization_field`,`id_lang`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_customized_data`
--

DROP TABLE IF EXISTS `ps_customized_data`;
CREATE TABLE IF NOT EXISTS `ps_customized_data` (
  `id_customization` int UNSIGNED NOT NULL,
  `type` tinyint(1) NOT NULL,
  `index` int NOT NULL,
  `value` varchar(255) NOT NULL,
  `id_module` int NOT NULL DEFAULT '0',
  `price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `weight` decimal(20,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`id_customization`,`type`,`index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_date_range`
--

DROP TABLE IF EXISTS `ps_date_range`;
CREATE TABLE IF NOT EXISTS `ps_date_range` (
  `id_date_range` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `time_start` datetime NOT NULL,
  `time_end` datetime NOT NULL,
  PRIMARY KEY (`id_date_range`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_delivery`
--

DROP TABLE IF EXISTS `ps_delivery`;
CREATE TABLE IF NOT EXISTS `ps_delivery` (
  `id_delivery` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop` int UNSIGNED DEFAULT NULL,
  `id_shop_group` int UNSIGNED DEFAULT NULL,
  `id_carrier` int UNSIGNED NOT NULL,
  `id_range_price` int UNSIGNED DEFAULT NULL,
  `id_range_weight` int UNSIGNED DEFAULT NULL,
  `id_zone` int UNSIGNED NOT NULL,
  `price` decimal(20,6) NOT NULL,
  PRIMARY KEY (`id_delivery`),
  KEY `id_zone` (`id_zone`),
  KEY `id_carrier` (`id_carrier`,`id_zone`),
  KEY `id_range_price` (`id_range_price`),
  KEY `id_range_weight` (`id_range_weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_emailsubscription`
--

DROP TABLE IF EXISTS `ps_emailsubscription`;
CREATE TABLE IF NOT EXISTS `ps_emailsubscription` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_shop_group` int UNSIGNED NOT NULL DEFAULT '1',
  `email` varchar(255) NOT NULL,
  `newsletter_date_add` datetime DEFAULT NULL,
  `ip_registration_newsletter` varchar(15) NOT NULL,
  `http_referer` varchar(255) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `id_lang` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_employee`
--

DROP TABLE IF EXISTS `ps_employee`;
CREATE TABLE IF NOT EXISTS `ps_employee` (
  `id_employee` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_profile` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL DEFAULT '0',
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `last_passwd_gen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `stats_date_from` date DEFAULT NULL,
  `stats_date_to` date DEFAULT NULL,
  `stats_compare_from` date DEFAULT NULL,
  `stats_compare_to` date DEFAULT NULL,
  `stats_compare_option` int UNSIGNED NOT NULL DEFAULT '1',
  `preselect_date_range` varchar(32) DEFAULT NULL,
  `bo_color` varchar(32) DEFAULT NULL,
  `bo_theme` varchar(32) DEFAULT NULL,
  `bo_css` varchar(64) DEFAULT NULL,
  `default_tab` int UNSIGNED NOT NULL DEFAULT '0',
  `bo_width` int UNSIGNED NOT NULL DEFAULT '0',
  `bo_menu` tinyint(1) NOT NULL DEFAULT '1',
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `optin` tinyint UNSIGNED DEFAULT NULL,
  `id_last_order` int UNSIGNED NOT NULL DEFAULT '0',
  `id_last_customer_message` int UNSIGNED NOT NULL DEFAULT '0',
  `id_last_customer` int UNSIGNED NOT NULL DEFAULT '0',
  `last_connection_date` date DEFAULT NULL,
  `reset_password_token` varchar(40) DEFAULT NULL,
  `reset_password_validity` datetime DEFAULT NULL,
  `has_enabled_gravatar` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_employee`),
  KEY `employee_login` (`email`,`passwd`),
  KEY `id_employee_passwd` (`id_employee`,`passwd`),
  KEY `id_profile` (`id_profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_employee_account`
--

DROP TABLE IF EXISTS `ps_employee_account`;
CREATE TABLE IF NOT EXISTS `ps_employee_account` (
  `id_employee_account` int NOT NULL AUTO_INCREMENT,
  `id_employee` int NOT NULL,
  `email` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_employee_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_employee_session`
--

DROP TABLE IF EXISTS `ps_employee_session`;
CREATE TABLE IF NOT EXISTS `ps_employee_session` (
  `id_employee_session` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_employee` int UNSIGNED DEFAULT NULL,
  `token` varchar(40) DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_employee_session`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_employee_shop`
--

DROP TABLE IF EXISTS `ps_employee_shop`;
CREATE TABLE IF NOT EXISTS `ps_employee_shop` (
  `id_employee` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_employee`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_eventbus_incremental_sync`
--

DROP TABLE IF EXISTS `ps_eventbus_incremental_sync`;
CREATE TABLE IF NOT EXISTS `ps_eventbus_incremental_sync` (
  `type` varchar(50) NOT NULL,
  `action` varchar(50) NOT NULL DEFAULT 'upsert',
  `id_object` varchar(50) NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  `lang_iso` varchar(3) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`type`,`id_object`,`id_shop`,`lang_iso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_eventbus_job`
--

DROP TABLE IF EXISTS `ps_eventbus_job`;
CREATE TABLE IF NOT EXISTS `ps_eventbus_job` (
  `job_id` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_eventbus_live_sync`
--

DROP TABLE IF EXISTS `ps_eventbus_live_sync`;
CREATE TABLE IF NOT EXISTS `ps_eventbus_live_sync` (
  `shop_content` varchar(50) NOT NULL,
  `last_change_at` datetime NOT NULL,
  PRIMARY KEY (`shop_content`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_eventbus_type_sync`
--

DROP TABLE IF EXISTS `ps_eventbus_type_sync`;
CREATE TABLE IF NOT EXISTS `ps_eventbus_type_sync` (
  `type` varchar(50) NOT NULL,
  `offset` int UNSIGNED NOT NULL DEFAULT '0',
  `id_shop` int UNSIGNED NOT NULL,
  `lang_iso` varchar(3) NOT NULL,
  `full_sync_finished` tinyint(1) NOT NULL DEFAULT '0',
  `last_sync_date` datetime NOT NULL,
  PRIMARY KEY (`type`,`id_shop`,`lang_iso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_fb_category_match`
--

DROP TABLE IF EXISTS `ps_fb_category_match`;
CREATE TABLE IF NOT EXISTS `ps_fb_category_match` (
  `id_category` int NOT NULL,
  `google_category_id` int NOT NULL,
  `google_category_name` varchar(255) NOT NULL,
  `google_category_parent_id` int NOT NULL,
  `google_category_parent_name` varchar(255) NOT NULL,
  `is_parent_category` tinyint(1) DEFAULT NULL,
  `id_shop` int NOT NULL,
  PRIMARY KEY (`id_category`,`id_shop`),
  KEY `id_category` (`id_category`,`google_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_feature`
--

DROP TABLE IF EXISTS `ps_feature`;
CREATE TABLE IF NOT EXISTS `ps_feature` (
  `id_feature` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_feature`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_feature_flag`
--

DROP TABLE IF EXISTS `ps_feature_flag`;
CREATE TABLE IF NOT EXISTS `ps_feature_flag` (
  `id_feature_flag` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `label_wording` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `label_domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description_wording` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `description_domain` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `stability` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'beta',
  PRIMARY KEY (`id_feature_flag`),
  UNIQUE KEY `UNIQ_91700F175E237E06` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_feature_lang`
--

DROP TABLE IF EXISTS `ps_feature_lang`;
CREATE TABLE IF NOT EXISTS `ps_feature_lang` (
  `id_feature` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_feature`,`id_lang`),
  KEY `id_lang` (`id_lang`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_feature_product`
--

DROP TABLE IF EXISTS `ps_feature_product`;
CREATE TABLE IF NOT EXISTS `ps_feature_product` (
  `id_feature` int UNSIGNED NOT NULL,
  `id_product` int UNSIGNED NOT NULL,
  `id_feature_value` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_feature`,`id_product`,`id_feature_value`),
  KEY `id_feature_value` (`id_feature_value`),
  KEY `id_product` (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_feature_shop`
--

DROP TABLE IF EXISTS `ps_feature_shop`;
CREATE TABLE IF NOT EXISTS `ps_feature_shop` (
  `id_feature` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_feature`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_feature_value`
--

DROP TABLE IF EXISTS `ps_feature_value`;
CREATE TABLE IF NOT EXISTS `ps_feature_value` (
  `id_feature_value` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_feature` int UNSIGNED NOT NULL,
  `custom` tinyint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id_feature_value`),
  KEY `feature` (`id_feature`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_feature_value_lang`
--

DROP TABLE IF EXISTS `ps_feature_value_lang`;
CREATE TABLE IF NOT EXISTS `ps_feature_value_lang` (
  `id_feature_value` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_feature_value`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_ganalytics`
--

DROP TABLE IF EXISTS `ps_ganalytics`;
CREATE TABLE IF NOT EXISTS `ps_ganalytics` (
  `id_google_analytics` int NOT NULL AUTO_INCREMENT,
  `id_order` int NOT NULL,
  `id_customer` int NOT NULL,
  `id_shop` int NOT NULL,
  `sent` tinyint(1) DEFAULT NULL,
  `refund_sent` tinyint(1) DEFAULT NULL,
  `date_add` datetime DEFAULT NULL,
  PRIMARY KEY (`id_google_analytics`),
  KEY `id_order` (`id_order`),
  KEY `sent` (`sent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_ganalytics_data`
--

DROP TABLE IF EXISTS `ps_ganalytics_data`;
CREATE TABLE IF NOT EXISTS `ps_ganalytics_data` (
  `id_cart` int NOT NULL,
  `id_shop` int NOT NULL,
  `data` text,
  PRIMARY KEY (`id_cart`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_gender`
--

DROP TABLE IF EXISTS `ps_gender`;
CREATE TABLE IF NOT EXISTS `ps_gender` (
  `id_gender` int NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_gender`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_gender_lang`
--

DROP TABLE IF EXISTS `ps_gender_lang`;
CREATE TABLE IF NOT EXISTS `ps_gender_lang` (
  `id_gender` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id_gender`,`id_lang`),
  KEY `id_gender` (`id_gender`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_group`
--

DROP TABLE IF EXISTS `ps_group`;
CREATE TABLE IF NOT EXISTS `ps_group` (
  `id_group` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `reduction` decimal(5,2) NOT NULL DEFAULT '0.00',
  `price_display_method` tinyint NOT NULL DEFAULT '0',
  `show_prices` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_group_lang`
--

DROP TABLE IF EXISTS `ps_group_lang`;
CREATE TABLE IF NOT EXISTS `ps_group_lang` (
  `id_group` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id_group`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_group_reduction`
--

DROP TABLE IF EXISTS `ps_group_reduction`;
CREATE TABLE IF NOT EXISTS `ps_group_reduction` (
  `id_group_reduction` mediumint UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_group` int UNSIGNED NOT NULL,
  `id_category` int UNSIGNED NOT NULL,
  `reduction` decimal(5,4) NOT NULL,
  PRIMARY KEY (`id_group_reduction`),
  UNIQUE KEY `id_group` (`id_group`,`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_group_shop`
--

DROP TABLE IF EXISTS `ps_group_shop`;
CREATE TABLE IF NOT EXISTS `ps_group_shop` (
  `id_group` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_group`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_gsitemap_sitemap`
--

DROP TABLE IF EXISTS `ps_gsitemap_sitemap`;
CREATE TABLE IF NOT EXISTS `ps_gsitemap_sitemap` (
  `link` varchar(255) DEFAULT NULL,
  `id_shop` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_guest`
--

DROP TABLE IF EXISTS `ps_guest`;
CREATE TABLE IF NOT EXISTS `ps_guest` (
  `id_guest` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_operating_system` int UNSIGNED DEFAULT NULL,
  `id_web_browser` int UNSIGNED DEFAULT NULL,
  `id_customer` int UNSIGNED DEFAULT NULL,
  `javascript` tinyint(1) DEFAULT '0',
  `screen_resolution_x` smallint UNSIGNED DEFAULT NULL,
  `screen_resolution_y` smallint UNSIGNED DEFAULT NULL,
  `screen_color` tinyint UNSIGNED DEFAULT NULL,
  `sun_java` tinyint(1) DEFAULT NULL,
  `adobe_flash` tinyint(1) DEFAULT NULL,
  `adobe_director` tinyint(1) DEFAULT NULL,
  `apple_quicktime` tinyint(1) DEFAULT NULL,
  `real_player` tinyint(1) DEFAULT NULL,
  `windows_media` tinyint(1) DEFAULT NULL,
  `accept_language` varchar(8) DEFAULT NULL,
  `mobile_theme` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_guest`),
  KEY `id_customer` (`id_customer`),
  KEY `id_operating_system` (`id_operating_system`),
  KEY `id_web_browser` (`id_web_browser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_homeslider`
--

DROP TABLE IF EXISTS `ps_homeslider`;
CREATE TABLE IF NOT EXISTS `ps_homeslider` (
  `id_homeslider_slides` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_homeslider_slides`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_homeslider_slides`
--

DROP TABLE IF EXISTS `ps_homeslider_slides`;
CREATE TABLE IF NOT EXISTS `ps_homeslider_slides` (
  `id_homeslider_slides` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_homeslider_slides`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_homeslider_slides_lang`
--

DROP TABLE IF EXISTS `ps_homeslider_slides_lang`;
CREATE TABLE IF NOT EXISTS `ps_homeslider_slides_lang` (
  `id_homeslider_slides` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `legend` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id_homeslider_slides`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_hook`
--

DROP TABLE IF EXISTS `ps_hook`;
CREATE TABLE IF NOT EXISTS `ps_hook` (
  `id_hook` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext,
  `active` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `position` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_hook`),
  UNIQUE KEY `hook_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_hook_alias`
--

DROP TABLE IF EXISTS `ps_hook_alias`;
CREATE TABLE IF NOT EXISTS `ps_hook_alias` (
  `id_hook_alias` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `alias` varchar(191) NOT NULL,
  `name` varchar(191) NOT NULL,
  PRIMARY KEY (`id_hook_alias`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_hook_module`
--

DROP TABLE IF EXISTS `ps_hook_module`;
CREATE TABLE IF NOT EXISTS `ps_hook_module` (
  `id_module` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_hook` int UNSIGNED NOT NULL,
  `position` tinyint UNSIGNED NOT NULL,
  PRIMARY KEY (`id_module`,`id_hook`,`id_shop`),
  KEY `id_hook` (`id_hook`),
  KEY `id_module` (`id_module`),
  KEY `position` (`id_shop`,`position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_hook_module_exceptions`
--

DROP TABLE IF EXISTS `ps_hook_module_exceptions`;
CREATE TABLE IF NOT EXISTS `ps_hook_module_exceptions` (
  `id_hook_module_exceptions` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_module` int UNSIGNED NOT NULL,
  `id_hook` int UNSIGNED NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_hook_module_exceptions`),
  KEY `id_module` (`id_module`),
  KEY `id_hook` (`id_hook`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_image`
--

DROP TABLE IF EXISTS `ps_image`;
CREATE TABLE IF NOT EXISTS `ps_image` (
  `id_image` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product` int UNSIGNED NOT NULL,
  `position` smallint UNSIGNED NOT NULL DEFAULT '0',
  `cover` tinyint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id_image`),
  UNIQUE KEY `id_product_cover` (`id_product`,`cover`),
  UNIQUE KEY `idx_product_image` (`id_image`,`id_product`,`cover`),
  KEY `image_product` (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_image_lang`
--

DROP TABLE IF EXISTS `ps_image_lang`;
CREATE TABLE IF NOT EXISTS `ps_image_lang` (
  `id_image` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `legend` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_image`,`id_lang`),
  KEY `id_image` (`id_image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_image_shop`
--

DROP TABLE IF EXISTS `ps_image_shop`;
CREATE TABLE IF NOT EXISTS `ps_image_shop` (
  `id_product` int UNSIGNED NOT NULL,
  `id_image` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  `cover` tinyint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id_image`,`id_shop`),
  UNIQUE KEY `id_product` (`id_product`,`id_shop`,`cover`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_image_type`
--

DROP TABLE IF EXISTS `ps_image_type`;
CREATE TABLE IF NOT EXISTS `ps_image_type` (
  `id_image_type` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `width` int UNSIGNED NOT NULL,
  `height` int UNSIGNED NOT NULL,
  `products` tinyint(1) NOT NULL DEFAULT '1',
  `categories` tinyint(1) NOT NULL DEFAULT '1',
  `manufacturers` tinyint(1) NOT NULL DEFAULT '1',
  `suppliers` tinyint(1) NOT NULL DEFAULT '1',
  `stores` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_image_type`),
  KEY `image_type_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_import_match`
--

DROP TABLE IF EXISTS `ps_import_match`;
CREATE TABLE IF NOT EXISTS `ps_import_match` (
  `id_import_match` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `match` mediumtext NOT NULL,
  `skip` int NOT NULL,
  PRIMARY KEY (`id_import_match`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_info`
--

DROP TABLE IF EXISTS `ps_info`;
CREATE TABLE IF NOT EXISTS `ps_info` (
  `id_info` int UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_info`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_info_lang`
--

DROP TABLE IF EXISTS `ps_info_lang`;
CREATE TABLE IF NOT EXISTS `ps_info_lang` (
  `id_info` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id_info`,`id_lang`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_info_shop`
--

DROP TABLE IF EXISTS `ps_info_shop`;
CREATE TABLE IF NOT EXISTS `ps_info_shop` (
  `id_info` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_info`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_lang`
--

DROP TABLE IF EXISTS `ps_lang`;
CREATE TABLE IF NOT EXISTS `ps_lang` (
  `id_lang` int NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `iso_code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_code` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locale` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_format_lite` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_format_full` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_rtl` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_lang_shop`
--

DROP TABLE IF EXISTS `ps_lang_shop`;
CREATE TABLE IF NOT EXISTS `ps_lang_shop` (
  `id_lang` int NOT NULL,
  `id_shop` int NOT NULL,
  PRIMARY KEY (`id_lang`,`id_shop`),
  KEY `IDX_2F43BFC7BA299860` (`id_lang`),
  KEY `IDX_2F43BFC7274A50A0` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_layered_category`
--

DROP TABLE IF EXISTS `ps_layered_category`;
CREATE TABLE IF NOT EXISTS `ps_layered_category` (
  `id_layered_category` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop` int UNSIGNED NOT NULL,
  `controller` varchar(64) NOT NULL,
  `id_category` int UNSIGNED NOT NULL,
  `id_value` int UNSIGNED DEFAULT '0',
  `type` enum('category','id_feature','id_attribute_group','availability','condition','manufacturer','weight','price','extras') NOT NULL,
  `position` int UNSIGNED NOT NULL,
  `filter_type` int UNSIGNED NOT NULL DEFAULT '0',
  `filter_show_limit` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_layered_category`),
  KEY `id_category_shop` (`id_category`,`id_shop`,`type`,`id_value`,`position`),
  KEY `id_category` (`id_category`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_layered_filter`
--

DROP TABLE IF EXISTS `ps_layered_filter`;
CREATE TABLE IF NOT EXISTS `ps_layered_filter` (
  `id_layered_filter` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `filters` longtext,
  `n_categories` int UNSIGNED NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_layered_filter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_layered_filter_block`
--

DROP TABLE IF EXISTS `ps_layered_filter_block`;
CREATE TABLE IF NOT EXISTS `ps_layered_filter_block` (
  `hash` char(32) NOT NULL DEFAULT '',
  `data` text,
  PRIMARY KEY (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_layered_filter_shop`
--

DROP TABLE IF EXISTS `ps_layered_filter_shop`;
CREATE TABLE IF NOT EXISTS `ps_layered_filter_shop` (
  `id_layered_filter` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_layered_filter`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_layered_indexable_attribute_group`
--

DROP TABLE IF EXISTS `ps_layered_indexable_attribute_group`;
CREATE TABLE IF NOT EXISTS `ps_layered_indexable_attribute_group` (
  `id_attribute_group` int NOT NULL,
  `indexable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_attribute_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_layered_indexable_attribute_group_lang_value`
--

DROP TABLE IF EXISTS `ps_layered_indexable_attribute_group_lang_value`;
CREATE TABLE IF NOT EXISTS `ps_layered_indexable_attribute_group_lang_value` (
  `id_attribute_group` int NOT NULL,
  `id_lang` int NOT NULL,
  `url_name` varchar(128) DEFAULT NULL,
  `meta_title` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_attribute_group`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_layered_indexable_attribute_lang_value`
--

DROP TABLE IF EXISTS `ps_layered_indexable_attribute_lang_value`;
CREATE TABLE IF NOT EXISTS `ps_layered_indexable_attribute_lang_value` (
  `id_attribute` int NOT NULL,
  `id_lang` int NOT NULL,
  `url_name` varchar(128) DEFAULT NULL,
  `meta_title` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_attribute`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_layered_indexable_feature`
--

DROP TABLE IF EXISTS `ps_layered_indexable_feature`;
CREATE TABLE IF NOT EXISTS `ps_layered_indexable_feature` (
  `id_feature` int NOT NULL,
  `indexable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_feature`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_layered_indexable_feature_lang_value`
--

DROP TABLE IF EXISTS `ps_layered_indexable_feature_lang_value`;
CREATE TABLE IF NOT EXISTS `ps_layered_indexable_feature_lang_value` (
  `id_feature` int NOT NULL,
  `id_lang` int NOT NULL,
  `url_name` varchar(128) NOT NULL,
  `meta_title` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_feature`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_layered_indexable_feature_value_lang_value`
--

DROP TABLE IF EXISTS `ps_layered_indexable_feature_value_lang_value`;
CREATE TABLE IF NOT EXISTS `ps_layered_indexable_feature_value_lang_value` (
  `id_feature_value` int NOT NULL,
  `id_lang` int NOT NULL,
  `url_name` varchar(128) DEFAULT NULL,
  `meta_title` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_feature_value`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_layered_price_index`
--

DROP TABLE IF EXISTS `ps_layered_price_index`;
CREATE TABLE IF NOT EXISTS `ps_layered_price_index` (
  `id_product` int NOT NULL,
  `id_currency` int NOT NULL,
  `id_shop` int NOT NULL,
  `price_min` decimal(20,6) NOT NULL,
  `price_max` decimal(20,6) NOT NULL,
  `id_country` int NOT NULL,
  PRIMARY KEY (`id_product`,`id_currency`,`id_shop`,`id_country`),
  KEY `id_currency` (`id_currency`),
  KEY `price_min` (`price_min`),
  KEY `price_max` (`price_max`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_layered_product_attribute`
--

DROP TABLE IF EXISTS `ps_layered_product_attribute`;
CREATE TABLE IF NOT EXISTS `ps_layered_product_attribute` (
  `id_attribute` int UNSIGNED NOT NULL,
  `id_product` int UNSIGNED NOT NULL,
  `id_attribute_group` int UNSIGNED NOT NULL DEFAULT '0',
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_attribute`,`id_product`,`id_shop`),
  UNIQUE KEY `id_attribute_group` (`id_attribute_group`,`id_attribute`,`id_product`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_linksmenutop`
--

DROP TABLE IF EXISTS `ps_linksmenutop`;
CREATE TABLE IF NOT EXISTS `ps_linksmenutop` (
  `id_linksmenutop` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop` int UNSIGNED NOT NULL,
  `new_window` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_linksmenutop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_linksmenutop_lang`
--

DROP TABLE IF EXISTS `ps_linksmenutop_lang`;
CREATE TABLE IF NOT EXISTS `ps_linksmenutop_lang` (
  `id_linksmenutop` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  `label` varchar(128) NOT NULL,
  `link` varchar(128) NOT NULL,
  KEY `id_linksmenutop` (`id_linksmenutop`,`id_lang`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_link_block`
--

DROP TABLE IF EXISTS `ps_link_block`;
CREATE TABLE IF NOT EXISTS `ps_link_block` (
  `id_link_block` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_hook` int UNSIGNED DEFAULT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  `content` text,
  PRIMARY KEY (`id_link_block`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_link_block_lang`
--

DROP TABLE IF EXISTS `ps_link_block_lang`;
CREATE TABLE IF NOT EXISTS `ps_link_block_lang` (
  `id_link_block` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(40) NOT NULL DEFAULT '',
  `custom_content` text,
  PRIMARY KEY (`id_link_block`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_link_block_shop`
--

DROP TABLE IF EXISTS `ps_link_block_shop`;
CREATE TABLE IF NOT EXISTS `ps_link_block_shop` (
  `id_link_block` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop` int UNSIGNED NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_link_block`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_log`
--

DROP TABLE IF EXISTS `ps_log`;
CREATE TABLE IF NOT EXISTS `ps_log` (
  `id_log` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `severity` tinyint(1) NOT NULL,
  `error_code` int DEFAULT NULL,
  `message` mediumtext NOT NULL,
  `object_type` varchar(32) DEFAULT NULL,
  `object_id` int UNSIGNED DEFAULT NULL,
  `id_shop` int UNSIGNED DEFAULT NULL,
  `id_shop_group` int UNSIGNED DEFAULT NULL,
  `id_lang` int UNSIGNED DEFAULT NULL,
  `in_all_shops` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `id_employee` int UNSIGNED DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_mail`
--

DROP TABLE IF EXISTS `ps_mail`;
CREATE TABLE IF NOT EXISTS `ps_mail` (
  `id_mail` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `recipient` varchar(126) NOT NULL,
  `template` varchar(62) NOT NULL,
  `subject` varchar(254) NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mail`),
  KEY `recipient` (`recipient`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_mailalert_customer_oos`
--

DROP TABLE IF EXISTS `ps_mailalert_customer_oos`;
CREATE TABLE IF NOT EXISTS `ps_mailalert_customer_oos` (
  `id_customer` int UNSIGNED NOT NULL,
  `customer_email` varchar(128) NOT NULL,
  `id_product` int UNSIGNED NOT NULL,
  `id_product_attribute` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_customer`,`customer_email`,`id_product`,`id_product_attribute`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_manufacturer`
--

DROP TABLE IF EXISTS `ps_manufacturer`;
CREATE TABLE IF NOT EXISTS `ps_manufacturer` (
  `id_manufacturer` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_manufacturer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_manufacturer_lang`
--

DROP TABLE IF EXISTS `ps_manufacturer_lang`;
CREATE TABLE IF NOT EXISTS `ps_manufacturer_lang` (
  `id_manufacturer` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `description` mediumtext,
  `short_description` mediumtext,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id_manufacturer`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_manufacturer_shop`
--

DROP TABLE IF EXISTS `ps_manufacturer_shop`;
CREATE TABLE IF NOT EXISTS `ps_manufacturer_shop` (
  `id_manufacturer` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_manufacturer`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_mbo_api_config`
--

DROP TABLE IF EXISTS `ps_mbo_api_config`;
CREATE TABLE IF NOT EXISTS `ps_mbo_api_config` (
  `id_mbo_api_config` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `config_key` varchar(255) DEFAULT NULL,
  `config_value` varchar(255) DEFAULT NULL,
  `ps_version` varchar(255) DEFAULT NULL,
  `mbo_version` varchar(255) DEFAULT NULL,
  `applied` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_mbo_api_config`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_memcached_servers`
--

DROP TABLE IF EXISTS `ps_memcached_servers`;
CREATE TABLE IF NOT EXISTS `ps_memcached_servers` (
  `id_memcached_server` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` varchar(254) NOT NULL,
  `port` int UNSIGNED NOT NULL,
  `weight` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_memcached_server`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_message`
--

DROP TABLE IF EXISTS `ps_message`;
CREATE TABLE IF NOT EXISTS `ps_message` (
  `id_message` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_cart` int UNSIGNED DEFAULT NULL,
  `id_customer` int UNSIGNED NOT NULL,
  `id_employee` int UNSIGNED DEFAULT NULL,
  `id_order` int UNSIGNED NOT NULL,
  `message` mediumtext NOT NULL,
  `private` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_message`),
  KEY `message_order` (`id_order`),
  KEY `id_cart` (`id_cart`),
  KEY `id_customer` (`id_customer`),
  KEY `id_employee` (`id_employee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_message_readed`
--

DROP TABLE IF EXISTS `ps_message_readed`;
CREATE TABLE IF NOT EXISTS `ps_message_readed` (
  `id_message` int UNSIGNED NOT NULL,
  `id_employee` int UNSIGNED NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_message`,`id_employee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_meta`
--

DROP TABLE IF EXISTS `ps_meta`;
CREATE TABLE IF NOT EXISTS `ps_meta` (
  `id_meta` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `page` varchar(64) NOT NULL,
  `configurable` tinyint UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_meta`),
  UNIQUE KEY `page` (`page`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_meta_lang`
--

DROP TABLE IF EXISTS `ps_meta_lang`;
CREATE TABLE IF NOT EXISTS `ps_meta_lang` (
  `id_meta` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_lang` int UNSIGNED NOT NULL,
  `title` varchar(128) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `url_rewrite` varchar(254) NOT NULL,
  PRIMARY KEY (`id_meta`,`id_shop`,`id_lang`),
  KEY `id_shop` (`id_shop`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_module`
--

DROP TABLE IF EXISTS `ps_module`;
CREATE TABLE IF NOT EXISTS `ps_module` (
  `id_module` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `version` varchar(8) NOT NULL,
  PRIMARY KEY (`id_module`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_module_access`
--

DROP TABLE IF EXISTS `ps_module_access`;
CREATE TABLE IF NOT EXISTS `ps_module_access` (
  `id_profile` int UNSIGNED NOT NULL,
  `id_authorization_role` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_profile`,`id_authorization_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_module_carrier`
--

DROP TABLE IF EXISTS `ps_module_carrier`;
CREATE TABLE IF NOT EXISTS `ps_module_carrier` (
  `id_module` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_reference` int NOT NULL,
  PRIMARY KEY (`id_module`,`id_shop`,`id_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_module_country`
--

DROP TABLE IF EXISTS `ps_module_country`;
CREATE TABLE IF NOT EXISTS `ps_module_country` (
  `id_module` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_country` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_module`,`id_shop`,`id_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_module_currency`
--

DROP TABLE IF EXISTS `ps_module_currency`;
CREATE TABLE IF NOT EXISTS `ps_module_currency` (
  `id_module` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_currency` int NOT NULL,
  PRIMARY KEY (`id_module`,`id_shop`,`id_currency`),
  KEY `id_module` (`id_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_module_group`
--

DROP TABLE IF EXISTS `ps_module_group`;
CREATE TABLE IF NOT EXISTS `ps_module_group` (
  `id_module` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_group` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_module`,`id_shop`,`id_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_module_history`
--

DROP TABLE IF EXISTS `ps_module_history`;
CREATE TABLE IF NOT EXISTS `ps_module_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_employee` int NOT NULL,
  `id_module` int NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_module_preference`
--

DROP TABLE IF EXISTS `ps_module_preference`;
CREATE TABLE IF NOT EXISTS `ps_module_preference` (
  `id_module_preference` int NOT NULL AUTO_INCREMENT,
  `id_employee` int NOT NULL,
  `module` varchar(191) NOT NULL,
  `interest` tinyint(1) DEFAULT NULL,
  `favorite` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id_module_preference`),
  UNIQUE KEY `employee_module` (`id_employee`,`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_module_shop`
--

DROP TABLE IF EXISTS `ps_module_shop`;
CREATE TABLE IF NOT EXISTS `ps_module_shop` (
  `id_module` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  `enable_device` tinyint(1) NOT NULL DEFAULT '7',
  PRIMARY KEY (`id_module`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_operating_system`
--

DROP TABLE IF EXISTS `ps_operating_system`;
CREATE TABLE IF NOT EXISTS `ps_operating_system` (
  `id_operating_system` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id_operating_system`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_orders`
--

DROP TABLE IF EXISTS `ps_orders`;
CREATE TABLE IF NOT EXISTS `ps_orders` (
  `id_order` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `reference` varchar(9) DEFAULT NULL,
  `id_shop_group` int UNSIGNED NOT NULL DEFAULT '1',
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_carrier` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `id_customer` int UNSIGNED NOT NULL,
  `id_cart` int UNSIGNED NOT NULL,
  `id_currency` int UNSIGNED NOT NULL,
  `id_address_delivery` int UNSIGNED NOT NULL,
  `id_address_invoice` int UNSIGNED NOT NULL,
  `current_state` int UNSIGNED NOT NULL,
  `secure_key` varchar(32) NOT NULL DEFAULT '-1',
  `payment` varchar(255) NOT NULL,
  `conversion_rate` decimal(13,6) NOT NULL DEFAULT '1.000000',
  `module` varchar(255) DEFAULT NULL,
  `recyclable` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `gift` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `gift_message` mediumtext,
  `mobile_theme` tinyint(1) NOT NULL DEFAULT '0',
  `total_discounts` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_discounts_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_discounts_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_paid` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_paid_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_paid_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_paid_real` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_products` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_products_wt` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `carrier_tax_rate` decimal(10,3) NOT NULL DEFAULT '0.000',
  `total_wrapping` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_wrapping_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_wrapping_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `round_mode` tinyint(1) NOT NULL DEFAULT '2',
  `round_type` tinyint(1) NOT NULL DEFAULT '1',
  `invoice_number` int UNSIGNED NOT NULL DEFAULT '0',
  `delivery_number` int UNSIGNED NOT NULL DEFAULT '0',
  `invoice_date` datetime NOT NULL,
  `delivery_date` datetime NOT NULL,
  `valid` int UNSIGNED NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `note` mediumtext,
  PRIMARY KEY (`id_order`),
  KEY `reference` (`reference`),
  KEY `id_customer` (`id_customer`),
  KEY `id_cart` (`id_cart`),
  KEY `invoice_number` (`invoice_number`),
  KEY `id_carrier` (`id_carrier`),
  KEY `id_lang` (`id_lang`),
  KEY `id_currency` (`id_currency`),
  KEY `id_address_delivery` (`id_address_delivery`),
  KEY `id_address_invoice` (`id_address_invoice`),
  KEY `id_shop_group` (`id_shop_group`),
  KEY `current_state` (`current_state`),
  KEY `id_shop` (`id_shop`),
  KEY `date_add` (`date_add`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_carrier`
--

DROP TABLE IF EXISTS `ps_order_carrier`;
CREATE TABLE IF NOT EXISTS `ps_order_carrier` (
  `id_order_carrier` int NOT NULL AUTO_INCREMENT,
  `id_order` int UNSIGNED NOT NULL,
  `id_carrier` int UNSIGNED NOT NULL,
  `id_order_invoice` int UNSIGNED DEFAULT NULL,
  `weight` decimal(20,6) DEFAULT NULL,
  `shipping_cost_tax_excl` decimal(20,6) DEFAULT NULL,
  `shipping_cost_tax_incl` decimal(20,6) DEFAULT NULL,
  `tracking_number` varchar(64) DEFAULT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_order_carrier`),
  KEY `id_order` (`id_order`),
  KEY `id_carrier` (`id_carrier`),
  KEY `id_order_invoice` (`id_order_invoice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_cart_rule`
--

DROP TABLE IF EXISTS `ps_order_cart_rule`;
CREATE TABLE IF NOT EXISTS `ps_order_cart_rule` (
  `id_order_cart_rule` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_order` int UNSIGNED NOT NULL,
  `id_cart_rule` int UNSIGNED NOT NULL,
  `id_order_invoice` int UNSIGNED DEFAULT '0',
  `name` varchar(254) NOT NULL,
  `value` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `value_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `free_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_order_cart_rule`),
  KEY `id_order` (`id_order`),
  KEY `id_cart_rule` (`id_cart_rule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_detail`
--

DROP TABLE IF EXISTS `ps_order_detail`;
CREATE TABLE IF NOT EXISTS `ps_order_detail` (
  `id_order_detail` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_order` int UNSIGNED NOT NULL,
  `id_order_invoice` int DEFAULT NULL,
  `id_warehouse` int UNSIGNED DEFAULT '0',
  `id_shop` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `product_attribute_id` int UNSIGNED DEFAULT NULL,
  `id_customization` int UNSIGNED DEFAULT '0',
  `product_name` mediumtext NOT NULL,
  `product_quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `product_quantity_in_stock` int NOT NULL DEFAULT '0',
  `product_quantity_refunded` int UNSIGNED NOT NULL DEFAULT '0',
  `product_quantity_return` int UNSIGNED NOT NULL DEFAULT '0',
  `product_quantity_reinjected` int UNSIGNED NOT NULL DEFAULT '0',
  `product_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `reduction_percent` decimal(5,2) NOT NULL DEFAULT '0.00',
  `reduction_amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `reduction_amount_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `reduction_amount_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `group_reduction` decimal(5,2) NOT NULL DEFAULT '0.00',
  `product_quantity_discount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `product_ean13` varchar(13) DEFAULT NULL,
  `product_isbn` varchar(32) DEFAULT NULL,
  `product_upc` varchar(12) DEFAULT NULL,
  `product_mpn` varchar(40) DEFAULT NULL,
  `product_reference` varchar(64) DEFAULT NULL,
  `product_supplier_reference` varchar(64) DEFAULT NULL,
  `product_weight` decimal(20,6) NOT NULL,
  `id_tax_rules_group` int UNSIGNED DEFAULT '0',
  `tax_computation_method` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `tax_name` varchar(16) NOT NULL,
  `tax_rate` decimal(10,3) NOT NULL DEFAULT '0.000',
  `ecotax` decimal(17,6) NOT NULL DEFAULT '0.000000',
  `ecotax_tax_rate` decimal(5,3) NOT NULL DEFAULT '0.000',
  `discount_quantity_applied` tinyint(1) NOT NULL DEFAULT '0',
  `download_hash` varchar(255) DEFAULT NULL,
  `download_nb` int UNSIGNED DEFAULT '0',
  `download_deadline` datetime DEFAULT NULL,
  `total_price_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_price_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_price_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_price_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping_price_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping_price_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `purchase_supplier_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `original_product_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `original_wholesale_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_refunded_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_refunded_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`id_order_detail`),
  KEY `order_detail_order` (`id_order`),
  KEY `product_id` (`product_id`,`product_attribute_id`),
  KEY `product_attribute_id` (`product_attribute_id`),
  KEY `id_tax_rules_group` (`id_tax_rules_group`),
  KEY `id_order_id_order_detail` (`id_order`,`id_order_detail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_detail_tax`
--

DROP TABLE IF EXISTS `ps_order_detail_tax`;
CREATE TABLE IF NOT EXISTS `ps_order_detail_tax` (
  `id_order_detail` int NOT NULL,
  `id_tax` int NOT NULL,
  `unit_amount` decimal(16,6) NOT NULL DEFAULT '0.000000',
  `total_amount` decimal(16,6) NOT NULL DEFAULT '0.000000',
  KEY `id_order_detail` (`id_order_detail`),
  KEY `id_tax` (`id_tax`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_history`
--

DROP TABLE IF EXISTS `ps_order_history`;
CREATE TABLE IF NOT EXISTS `ps_order_history` (
  `id_order_history` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_employee` int UNSIGNED NOT NULL,
  `id_order` int UNSIGNED NOT NULL,
  `id_order_state` int UNSIGNED NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_order_history`),
  KEY `order_history_order` (`id_order`),
  KEY `id_employee` (`id_employee`),
  KEY `id_order_state` (`id_order_state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_invoice`
--

DROP TABLE IF EXISTS `ps_order_invoice`;
CREATE TABLE IF NOT EXISTS `ps_order_invoice` (
  `id_order_invoice` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_order` int NOT NULL,
  `number` int NOT NULL,
  `delivery_number` int NOT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `total_discount_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_discount_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_paid_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_paid_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_products` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_products_wt` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_shipping_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `shipping_tax_computation_method` int UNSIGNED NOT NULL,
  `total_wrapping_tax_excl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `total_wrapping_tax_incl` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `shop_address` mediumtext,
  `note` mediumtext,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_order_invoice`),
  KEY `id_order` (`id_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_invoice_payment`
--

DROP TABLE IF EXISTS `ps_order_invoice_payment`;
CREATE TABLE IF NOT EXISTS `ps_order_invoice_payment` (
  `id_order_invoice` int UNSIGNED NOT NULL,
  `id_order_payment` int UNSIGNED NOT NULL,
  `id_order` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_order_invoice`,`id_order_payment`),
  KEY `order_payment` (`id_order_payment`),
  KEY `id_order` (`id_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_invoice_tax`
--

DROP TABLE IF EXISTS `ps_order_invoice_tax`;
CREATE TABLE IF NOT EXISTS `ps_order_invoice_tax` (
  `id_order_invoice` int NOT NULL,
  `type` varchar(15) NOT NULL,
  `id_tax` int NOT NULL,
  `amount` decimal(10,6) NOT NULL DEFAULT '0.000000',
  KEY `id_tax` (`id_tax`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_message`
--

DROP TABLE IF EXISTS `ps_order_message`;
CREATE TABLE IF NOT EXISTS `ps_order_message` (
  `id_order_message` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_order_message`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_message_lang`
--

DROP TABLE IF EXISTS `ps_order_message_lang`;
CREATE TABLE IF NOT EXISTS `ps_order_message_lang` (
  `id_order_message` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `message` mediumtext NOT NULL,
  PRIMARY KEY (`id_order_message`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_payment`
--

DROP TABLE IF EXISTS `ps_order_payment`;
CREATE TABLE IF NOT EXISTS `ps_order_payment` (
  `id_order_payment` int NOT NULL AUTO_INCREMENT,
  `order_reference` varchar(9) DEFAULT NULL,
  `id_currency` int UNSIGNED NOT NULL,
  `amount` decimal(20,6) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `conversion_rate` decimal(13,6) NOT NULL DEFAULT '1.000000',
  `transaction_id` varchar(254) DEFAULT NULL,
  `card_number` varchar(254) DEFAULT NULL,
  `card_brand` varchar(254) DEFAULT NULL,
  `card_expiration` char(7) DEFAULT NULL,
  `card_holder` varchar(254) DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `id_employee` int DEFAULT NULL,
  PRIMARY KEY (`id_order_payment`),
  KEY `order_reference` (`order_reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_return`
--

DROP TABLE IF EXISTS `ps_order_return`;
CREATE TABLE IF NOT EXISTS `ps_order_return` (
  `id_order_return` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_customer` int UNSIGNED NOT NULL,
  `id_order` int UNSIGNED NOT NULL,
  `state` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `question` mediumtext NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_order_return`),
  KEY `order_return_customer` (`id_customer`),
  KEY `id_order` (`id_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_return_detail`
--

DROP TABLE IF EXISTS `ps_order_return_detail`;
CREATE TABLE IF NOT EXISTS `ps_order_return_detail` (
  `id_order_return` int UNSIGNED NOT NULL,
  `id_order_detail` int UNSIGNED NOT NULL,
  `id_customization` int UNSIGNED NOT NULL DEFAULT '0',
  `product_quantity` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_order_return`,`id_order_detail`,`id_customization`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_return_state`
--

DROP TABLE IF EXISTS `ps_order_return_state`;
CREATE TABLE IF NOT EXISTS `ps_order_return_state` (
  `id_order_return_state` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `color` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id_order_return_state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_return_state_lang`
--

DROP TABLE IF EXISTS `ps_order_return_state_lang`;
CREATE TABLE IF NOT EXISTS `ps_order_return_state_lang` (
  `id_order_return_state` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id_order_return_state`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_slip`
--

DROP TABLE IF EXISTS `ps_order_slip`;
CREATE TABLE IF NOT EXISTS `ps_order_slip` (
  `id_order_slip` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `conversion_rate` decimal(13,6) NOT NULL DEFAULT '1.000000',
  `id_customer` int UNSIGNED NOT NULL,
  `id_order` int UNSIGNED NOT NULL,
  `total_products_tax_excl` decimal(20,6) DEFAULT NULL,
  `total_products_tax_incl` decimal(20,6) DEFAULT NULL,
  `total_shipping_tax_excl` decimal(20,6) DEFAULT NULL,
  `total_shipping_tax_incl` decimal(20,6) DEFAULT NULL,
  `shipping_cost` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `shipping_cost_amount` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `partial` tinyint(1) NOT NULL,
  `order_slip_type` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_order_slip`),
  KEY `order_slip_customer` (`id_customer`),
  KEY `id_order` (`id_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_slip_detail`
--

DROP TABLE IF EXISTS `ps_order_slip_detail`;
CREATE TABLE IF NOT EXISTS `ps_order_slip_detail` (
  `id_order_slip` int UNSIGNED NOT NULL,
  `id_order_detail` int UNSIGNED NOT NULL,
  `product_quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `unit_price_tax_excl` decimal(20,6) DEFAULT NULL,
  `unit_price_tax_incl` decimal(20,6) DEFAULT NULL,
  `total_price_tax_excl` decimal(20,6) DEFAULT NULL,
  `total_price_tax_incl` decimal(20,6) DEFAULT NULL,
  `amount_tax_excl` decimal(20,6) DEFAULT NULL,
  `amount_tax_incl` decimal(20,6) DEFAULT NULL,
  PRIMARY KEY (`id_order_slip`,`id_order_detail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_state`
--

DROP TABLE IF EXISTS `ps_order_state`;
CREATE TABLE IF NOT EXISTS `ps_order_state` (
  `id_order_state` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice` tinyint UNSIGNED DEFAULT '0',
  `send_email` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `module_name` varchar(255) DEFAULT NULL,
  `color` varchar(32) DEFAULT NULL,
  `unremovable` tinyint UNSIGNED NOT NULL,
  `hidden` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `logable` tinyint(1) NOT NULL DEFAULT '0',
  `delivery` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `shipped` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `paid` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `pdf_invoice` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `pdf_delivery` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `deleted` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_order_state`),
  KEY `module_name` (`module_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_order_state_lang`
--

DROP TABLE IF EXISTS `ps_order_state_lang`;
CREATE TABLE IF NOT EXISTS `ps_order_state_lang` (
  `id_order_state` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `template` varchar(64) NOT NULL,
  PRIMARY KEY (`id_order_state`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pack`
--

DROP TABLE IF EXISTS `ps_pack`;
CREATE TABLE IF NOT EXISTS `ps_pack` (
  `id_product_pack` int UNSIGNED NOT NULL,
  `id_product_item` int UNSIGNED NOT NULL,
  `id_product_attribute_item` int UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_product_pack`,`id_product_item`,`id_product_attribute_item`),
  KEY `product_item` (`id_product_item`,`id_product_attribute_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_page`
--

DROP TABLE IF EXISTS `ps_page`;
CREATE TABLE IF NOT EXISTS `ps_page` (
  `id_page` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_page_type` int UNSIGNED NOT NULL,
  `id_object` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id_page`),
  KEY `id_page_type` (`id_page_type`),
  KEY `id_object` (`id_object`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pagenotfound`
--

DROP TABLE IF EXISTS `ps_pagenotfound`;
CREATE TABLE IF NOT EXISTS `ps_pagenotfound` (
  `id_pagenotfound` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_shop_group` int UNSIGNED NOT NULL DEFAULT '1',
  `request_uri` varchar(256) NOT NULL,
  `http_referer` varchar(256) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_pagenotfound`),
  KEY `date_add` (`date_add`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_page_type`
--

DROP TABLE IF EXISTS `ps_page_type`;
CREATE TABLE IF NOT EXISTS `ps_page_type` (
  `id_page_type` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id_page_type`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_page_viewed`
--

DROP TABLE IF EXISTS `ps_page_viewed`;
CREATE TABLE IF NOT EXISTS `ps_page_viewed` (
  `id_page` int UNSIGNED NOT NULL,
  `id_shop_group` int UNSIGNED NOT NULL DEFAULT '1',
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_date_range` int UNSIGNED NOT NULL,
  `counter` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_page`,`id_date_range`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product`
--

DROP TABLE IF EXISTS `ps_product`;
CREATE TABLE IF NOT EXISTS `ps_product` (
  `id_product` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_supplier` int UNSIGNED DEFAULT NULL,
  `id_manufacturer` int UNSIGNED DEFAULT NULL,
  `id_category_default` int UNSIGNED DEFAULT NULL,
  `id_shop_default` int UNSIGNED NOT NULL DEFAULT '1',
  `id_tax_rules_group` int UNSIGNED NOT NULL,
  `on_sale` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `online_only` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `ean13` varchar(13) DEFAULT NULL,
  `isbn` varchar(32) DEFAULT NULL,
  `upc` varchar(12) DEFAULT NULL,
  `mpn` varchar(40) DEFAULT NULL,
  `ecotax` decimal(17,6) NOT NULL DEFAULT '0.000000',
  `quantity` int NOT NULL DEFAULT '0',
  `minimal_quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `low_stock_threshold` int DEFAULT NULL,
  `low_stock_alert` tinyint(1) NOT NULL DEFAULT '0',
  `price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `wholesale_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unity` varchar(255) DEFAULT NULL,
  `unit_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_price_ratio` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `additional_shipping_cost` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `reference` varchar(64) DEFAULT NULL,
  `supplier_reference` varchar(64) DEFAULT NULL,
  `location` varchar(255) NOT NULL DEFAULT '',
  `width` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `height` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `depth` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `weight` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `out_of_stock` int UNSIGNED NOT NULL DEFAULT '2',
  `additional_delivery_times` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `quantity_discount` tinyint(1) DEFAULT '0',
  `customizable` tinyint NOT NULL DEFAULT '0',
  `uploadable_files` tinyint NOT NULL DEFAULT '0',
  `text_fields` tinyint NOT NULL DEFAULT '0',
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `redirect_type` enum('','404','410','301-product','302-product','301-category','302-category','200-displayed','404-displayed','410-displayed','default') NOT NULL DEFAULT 'default',
  `id_type_redirected` int UNSIGNED NOT NULL DEFAULT '0',
  `available_for_order` tinyint(1) NOT NULL DEFAULT '1',
  `available_date` date DEFAULT NULL,
  `show_condition` tinyint(1) NOT NULL DEFAULT '0',
  `condition` enum('new','used','refurbished') NOT NULL DEFAULT 'new',
  `show_price` tinyint(1) NOT NULL DEFAULT '1',
  `indexed` tinyint(1) NOT NULL DEFAULT '0',
  `visibility` enum('both','catalog','search','none') NOT NULL DEFAULT 'both',
  `cache_is_pack` tinyint(1) NOT NULL DEFAULT '0',
  `cache_has_attachments` tinyint(1) NOT NULL DEFAULT '0',
  `is_virtual` tinyint(1) NOT NULL DEFAULT '0',
  `cache_default_attribute` int UNSIGNED DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `advanced_stock_management` tinyint(1) NOT NULL DEFAULT '0',
  `pack_stock_type` int UNSIGNED NOT NULL DEFAULT '3',
  `state` int UNSIGNED NOT NULL DEFAULT '1',
  `product_type` enum('standard','pack','virtual','combinations','') NOT NULL DEFAULT '',
  PRIMARY KEY (`id_product`),
  KEY `reference_idx` (`reference`),
  KEY `supplier_reference_idx` (`supplier_reference`),
  KEY `product_supplier` (`id_supplier`),
  KEY `product_manufacturer` (`id_manufacturer`,`id_product`),
  KEY `id_category_default` (`id_category_default`),
  KEY `indexed` (`indexed`),
  KEY `date_add` (`date_add`),
  KEY `state` (`state`,`date_upd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_attachment`
--

DROP TABLE IF EXISTS `ps_product_attachment`;
CREATE TABLE IF NOT EXISTS `ps_product_attachment` (
  `id_product` int UNSIGNED NOT NULL,
  `id_attachment` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_product`,`id_attachment`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_attribute`
--

DROP TABLE IF EXISTS `ps_product_attribute`;
CREATE TABLE IF NOT EXISTS `ps_product_attribute` (
  `id_product_attribute` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product` int UNSIGNED NOT NULL,
  `reference` varchar(64) DEFAULT NULL,
  `supplier_reference` varchar(64) DEFAULT NULL,
  `ean13` varchar(13) DEFAULT NULL,
  `isbn` varchar(32) DEFAULT NULL,
  `upc` varchar(12) DEFAULT NULL,
  `mpn` varchar(40) DEFAULT NULL,
  `wholesale_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `ecotax` decimal(17,6) NOT NULL DEFAULT '0.000000',
  `weight` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_price_impact` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `default_on` tinyint UNSIGNED DEFAULT NULL,
  `minimal_quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `low_stock_threshold` int DEFAULT NULL,
  `low_stock_alert` tinyint(1) NOT NULL DEFAULT '0',
  `available_date` date DEFAULT NULL,
  PRIMARY KEY (`id_product_attribute`),
  UNIQUE KEY `product_default` (`id_product`,`default_on`),
  KEY `product_attribute_product` (`id_product`),
  KEY `reference` (`reference`),
  KEY `supplier_reference` (`supplier_reference`),
  KEY `id_product_id_product_attribute` (`id_product_attribute`,`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_attribute_combination`
--

DROP TABLE IF EXISTS `ps_product_attribute_combination`;
CREATE TABLE IF NOT EXISTS `ps_product_attribute_combination` (
  `id_attribute` int UNSIGNED NOT NULL,
  `id_product_attribute` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_attribute`,`id_product_attribute`),
  KEY `id_product_attribute` (`id_product_attribute`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_attribute_image`
--

DROP TABLE IF EXISTS `ps_product_attribute_image`;
CREATE TABLE IF NOT EXISTS `ps_product_attribute_image` (
  `id_product_attribute` int UNSIGNED NOT NULL,
  `id_image` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_product_attribute`,`id_image`),
  KEY `id_image` (`id_image`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_attribute_lang`
--

DROP TABLE IF EXISTS `ps_product_attribute_lang`;
CREATE TABLE IF NOT EXISTS `ps_product_attribute_lang` (
  `id_product_attribute` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `available_now` varchar(255) DEFAULT NULL,
  `available_later` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_product_attribute`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_attribute_shop`
--

DROP TABLE IF EXISTS `ps_product_attribute_shop`;
CREATE TABLE IF NOT EXISTS `ps_product_attribute_shop` (
  `id_product` int UNSIGNED NOT NULL,
  `id_product_attribute` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  `wholesale_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `ecotax` decimal(17,6) NOT NULL DEFAULT '0.000000',
  `weight` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_price_impact` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `default_on` tinyint UNSIGNED DEFAULT NULL,
  `minimal_quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `low_stock_threshold` int DEFAULT NULL,
  `low_stock_alert` tinyint(1) NOT NULL DEFAULT '0',
  `available_date` date DEFAULT NULL,
  PRIMARY KEY (`id_product_attribute`,`id_shop`),
  UNIQUE KEY `id_product` (`id_product`,`id_shop`,`default_on`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_carrier`
--

DROP TABLE IF EXISTS `ps_product_carrier`;
CREATE TABLE IF NOT EXISTS `ps_product_carrier` (
  `id_product` int UNSIGNED NOT NULL,
  `id_carrier_reference` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_product`,`id_carrier_reference`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_comment`
--

DROP TABLE IF EXISTS `ps_product_comment`;
CREATE TABLE IF NOT EXISTS `ps_product_comment` (
  `id_product_comment` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product` int UNSIGNED NOT NULL,
  `id_customer` int UNSIGNED NOT NULL,
  `id_guest` int UNSIGNED DEFAULT NULL,
  `title` varchar(64) DEFAULT NULL,
  `content` text NOT NULL,
  `customer_name` varchar(64) DEFAULT NULL,
  `grade` float UNSIGNED NOT NULL,
  `validate` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_product_comment`),
  KEY `id_product` (`id_product`),
  KEY `id_customer` (`id_customer`),
  KEY `id_guest` (`id_guest`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_comment_criterion`
--

DROP TABLE IF EXISTS `ps_product_comment_criterion`;
CREATE TABLE IF NOT EXISTS `ps_product_comment_criterion` (
  `id_product_comment_criterion` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product_comment_criterion_type` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_product_comment_criterion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_comment_criterion_category`
--

DROP TABLE IF EXISTS `ps_product_comment_criterion_category`;
CREATE TABLE IF NOT EXISTS `ps_product_comment_criterion_category` (
  `id_product_comment_criterion` int UNSIGNED NOT NULL,
  `id_category` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_product_comment_criterion`,`id_category`),
  KEY `id_category` (`id_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_comment_criterion_lang`
--

DROP TABLE IF EXISTS `ps_product_comment_criterion_lang`;
CREATE TABLE IF NOT EXISTS `ps_product_comment_criterion_lang` (
  `id_product_comment_criterion` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id_product_comment_criterion`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_comment_criterion_product`
--

DROP TABLE IF EXISTS `ps_product_comment_criterion_product`;
CREATE TABLE IF NOT EXISTS `ps_product_comment_criterion_product` (
  `id_product` int UNSIGNED NOT NULL,
  `id_product_comment_criterion` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_product`,`id_product_comment_criterion`),
  KEY `id_product_comment_criterion` (`id_product_comment_criterion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_comment_grade`
--

DROP TABLE IF EXISTS `ps_product_comment_grade`;
CREATE TABLE IF NOT EXISTS `ps_product_comment_grade` (
  `id_product_comment` int UNSIGNED NOT NULL,
  `id_product_comment_criterion` int UNSIGNED NOT NULL,
  `grade` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_product_comment`,`id_product_comment_criterion`),
  KEY `id_product_comment_criterion` (`id_product_comment_criterion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_comment_report`
--

DROP TABLE IF EXISTS `ps_product_comment_report`;
CREATE TABLE IF NOT EXISTS `ps_product_comment_report` (
  `id_product_comment` int UNSIGNED NOT NULL,
  `id_customer` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_product_comment`,`id_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_comment_usefulness`
--

DROP TABLE IF EXISTS `ps_product_comment_usefulness`;
CREATE TABLE IF NOT EXISTS `ps_product_comment_usefulness` (
  `id_product_comment` int UNSIGNED NOT NULL,
  `id_customer` int UNSIGNED NOT NULL,
  `usefulness` tinyint UNSIGNED NOT NULL,
  PRIMARY KEY (`id_product_comment`,`id_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_country_tax`
--

DROP TABLE IF EXISTS `ps_product_country_tax`;
CREATE TABLE IF NOT EXISTS `ps_product_country_tax` (
  `id_product` int NOT NULL,
  `id_country` int NOT NULL,
  `id_tax` int NOT NULL,
  PRIMARY KEY (`id_product`,`id_country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_download`
--

DROP TABLE IF EXISTS `ps_product_download`;
CREATE TABLE IF NOT EXISTS `ps_product_download` (
  `id_product_download` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product` int UNSIGNED NOT NULL,
  `display_filename` varchar(255) DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `date_expiration` datetime DEFAULT NULL,
  `nb_days_accessible` int UNSIGNED DEFAULT NULL,
  `nb_downloadable` int UNSIGNED DEFAULT '1',
  `active` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `is_shareable` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_product_download`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_group_reduction_cache`
--

DROP TABLE IF EXISTS `ps_product_group_reduction_cache`;
CREATE TABLE IF NOT EXISTS `ps_product_group_reduction_cache` (
  `id_product` int UNSIGNED NOT NULL,
  `id_group` int UNSIGNED NOT NULL,
  `reduction` decimal(5,4) NOT NULL,
  PRIMARY KEY (`id_product`,`id_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_lang`
--

DROP TABLE IF EXISTS `ps_product_lang`;
CREATE TABLE IF NOT EXISTS `ps_product_lang` (
  `id_product` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_lang` int UNSIGNED NOT NULL,
  `description` mediumtext,
  `description_short` mediumtext,
  `link_rewrite` varchar(128) NOT NULL,
  `meta_description` varchar(512) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_title` varchar(128) DEFAULT NULL,
  `name` varchar(128) NOT NULL,
  `available_now` varchar(255) DEFAULT NULL,
  `available_later` varchar(255) DEFAULT NULL,
  `delivery_in_stock` varchar(255) DEFAULT NULL,
  `delivery_out_stock` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_product`,`id_shop`,`id_lang`),
  KEY `id_lang` (`id_lang`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_sale`
--

DROP TABLE IF EXISTS `ps_product_sale`;
CREATE TABLE IF NOT EXISTS `ps_product_sale` (
  `id_product` int UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL DEFAULT '0',
  `sale_nbr` int UNSIGNED NOT NULL DEFAULT '0',
  `date_upd` date DEFAULT NULL,
  PRIMARY KEY (`id_product`),
  KEY `quantity` (`quantity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_shop`
--

DROP TABLE IF EXISTS `ps_product_shop`;
CREATE TABLE IF NOT EXISTS `ps_product_shop` (
  `id_product` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  `id_category_default` int UNSIGNED DEFAULT NULL,
  `id_tax_rules_group` int UNSIGNED NOT NULL,
  `on_sale` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `online_only` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `ecotax` decimal(17,6) NOT NULL DEFAULT '0.000000',
  `minimal_quantity` int UNSIGNED NOT NULL DEFAULT '1',
  `low_stock_threshold` int DEFAULT NULL,
  `low_stock_alert` tinyint(1) NOT NULL DEFAULT '0',
  `price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `wholesale_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unity` varchar(255) DEFAULT NULL,
  `unit_price` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `unit_price_ratio` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `additional_shipping_cost` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `customizable` tinyint NOT NULL DEFAULT '0',
  `uploadable_files` tinyint NOT NULL DEFAULT '0',
  `text_fields` tinyint NOT NULL DEFAULT '0',
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `redirect_type` enum('','404','410','301-product','302-product','301-category','302-category','200-displayed','404-displayed','410-displayed','default') NOT NULL DEFAULT 'default',
  `id_type_redirected` int UNSIGNED NOT NULL DEFAULT '0',
  `available_for_order` tinyint(1) NOT NULL DEFAULT '1',
  `available_date` date DEFAULT NULL,
  `show_condition` tinyint(1) NOT NULL DEFAULT '1',
  `condition` enum('new','used','refurbished') NOT NULL DEFAULT 'new',
  `show_price` tinyint(1) NOT NULL DEFAULT '1',
  `indexed` tinyint(1) NOT NULL DEFAULT '0',
  `visibility` enum('both','catalog','search','none') NOT NULL DEFAULT 'both',
  `cache_default_attribute` int UNSIGNED DEFAULT NULL,
  `advanced_stock_management` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `pack_stock_type` int UNSIGNED NOT NULL DEFAULT '3',
  PRIMARY KEY (`id_product`,`id_shop`),
  KEY `id_category_default` (`id_category_default`),
  KEY `date_add` (`date_add`,`active`,`visibility`),
  KEY `indexed` (`indexed`,`active`,`id_product`),
  KEY `shop_tax` (`id_shop`,`id_tax_rules_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_supplier`
--

DROP TABLE IF EXISTS `ps_product_supplier`;
CREATE TABLE IF NOT EXISTS `ps_product_supplier` (
  `id_product_supplier` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product` int UNSIGNED NOT NULL,
  `id_product_attribute` int UNSIGNED NOT NULL DEFAULT '0',
  `id_supplier` int UNSIGNED NOT NULL,
  `product_supplier_reference` varchar(64) DEFAULT NULL,
  `product_supplier_price_te` decimal(20,6) NOT NULL DEFAULT '0.000000',
  `id_currency` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_product_supplier`),
  UNIQUE KEY `id_product` (`id_product`,`id_product_attribute`,`id_supplier`),
  KEY `id_supplier` (`id_supplier`,`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_product_tag`
--

DROP TABLE IF EXISTS `ps_product_tag`;
CREATE TABLE IF NOT EXISTS `ps_product_tag` (
  `id_product` int UNSIGNED NOT NULL,
  `id_tag` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_product`,`id_tag`),
  KEY `id_tag` (`id_tag`),
  KEY `id_lang` (`id_lang`,`id_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_profile`
--

DROP TABLE IF EXISTS `ps_profile`;
CREATE TABLE IF NOT EXISTS `ps_profile` (
  `id_profile` int UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_profile_lang`
--

DROP TABLE IF EXISTS `ps_profile_lang`;
CREATE TABLE IF NOT EXISTS `ps_profile_lang` (
  `id_lang` int UNSIGNED NOT NULL,
  `id_profile` int UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  PRIMARY KEY (`id_profile`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pscheckout_authorization`
--

DROP TABLE IF EXISTS `ps_pscheckout_authorization`;
CREATE TABLE IF NOT EXISTS `ps_pscheckout_authorization` (
  `id` varchar(50) NOT NULL,
  `id_order` varchar(50) NOT NULL,
  `status` varchar(30) NOT NULL,
  `expiration_time` varchar(50) NOT NULL,
  `seller_protection` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pscheckout_capture`
--

DROP TABLE IF EXISTS `ps_pscheckout_capture`;
CREATE TABLE IF NOT EXISTS `ps_pscheckout_capture` (
  `id` varchar(50) NOT NULL,
  `id_order` varchar(50) NOT NULL,
  `status` varchar(30) NOT NULL,
  `final_capture` tinyint(1) NOT NULL,
  `created_at` varchar(50) NOT NULL,
  `updated_at` varchar(50) NOT NULL,
  `seller_protection` text,
  `seller_receivable_breakdown` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pscheckout_cart`
--

DROP TABLE IF EXISTS `ps_pscheckout_cart`;
CREATE TABLE IF NOT EXISTS `ps_pscheckout_cart` (
  `id_pscheckout_cart` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_cart` int UNSIGNED NOT NULL,
  `paypal_intent` varchar(20) DEFAULT 'CAPTURE',
  `paypal_order` varchar(20) DEFAULT NULL,
  `paypal_status` varchar(30) DEFAULT NULL,
  `paypal_funding` varchar(20) DEFAULT NULL,
  `paypal_token` text,
  `paypal_token_expire` datetime DEFAULT NULL,
  `paypal_authorization_expire` datetime DEFAULT NULL,
  `environment` varchar(20) DEFAULT NULL,
  `isExpressCheckout` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `isHostedFields` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_pscheckout_cart`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pscheckout_customer`
--

DROP TABLE IF EXISTS `ps_pscheckout_customer`;
CREATE TABLE IF NOT EXISTS `ps_pscheckout_customer` (
  `id_customer` int UNSIGNED NOT NULL,
  `paypal_customer_id` varchar(50) NOT NULL,
  PRIMARY KEY (`id_customer`,`paypal_customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pscheckout_funding_source`
--

DROP TABLE IF EXISTS `ps_pscheckout_funding_source`;
CREATE TABLE IF NOT EXISTS `ps_pscheckout_funding_source` (
  `name` varchar(20) NOT NULL,
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `position` tinyint UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`name`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pscheckout_order`
--

DROP TABLE IF EXISTS `ps_pscheckout_order`;
CREATE TABLE IF NOT EXISTS `ps_pscheckout_order` (
  `id` varchar(50) NOT NULL,
  `id_cart` int UNSIGNED NOT NULL,
  `status` varchar(30) NOT NULL,
  `intent` varchar(50) DEFAULT 'CAPTURE',
  `funding_source` varchar(50) NOT NULL,
  `payment_source` text,
  `environment` varchar(50) NOT NULL,
  `is_card_fields` tinyint(1) NOT NULL,
  `is_express_checkout` tinyint(1) NOT NULL,
  `customer_intent` varchar(50) DEFAULT NULL,
  `payment_token_id` varchar(50) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pscheckout_order_matrice`
--

DROP TABLE IF EXISTS `ps_pscheckout_order_matrice`;
CREATE TABLE IF NOT EXISTS `ps_pscheckout_order_matrice` (
  `id_order_matrice` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_order_prestashop` int UNSIGNED NOT NULL,
  `id_order_paypal` varchar(20) NOT NULL,
  PRIMARY KEY (`id_order_matrice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pscheckout_payment_token`
--

DROP TABLE IF EXISTS `ps_pscheckout_payment_token`;
CREATE TABLE IF NOT EXISTS `ps_pscheckout_payment_token` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `token_id` varchar(50) NOT NULL,
  `paypal_customer_id` varchar(50) NOT NULL,
  `payment_source` varchar(50) NOT NULL,
  `data` text NOT NULL,
  `merchant_id` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `is_favorite` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_id_merchant_id_paypal_customer_id` (`token_id`,`merchant_id`,`paypal_customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pscheckout_purchase_unit`
--

DROP TABLE IF EXISTS `ps_pscheckout_purchase_unit`;
CREATE TABLE IF NOT EXISTS `ps_pscheckout_purchase_unit` (
  `id_order` varchar(50) NOT NULL,
  `checksum` varchar(50) NOT NULL,
  `reference_id` varchar(50) NOT NULL,
  `items` text,
  PRIMARY KEY (`reference_id`,`id_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pscheckout_refund`
--

DROP TABLE IF EXISTS `ps_pscheckout_refund`;
CREATE TABLE IF NOT EXISTS `ps_pscheckout_refund` (
  `id` varchar(50) NOT NULL,
  `id_order` varchar(50) NOT NULL,
  `status` varchar(30) NOT NULL,
  `invoice_id` varchar(50) NOT NULL,
  `custom_id` varchar(50) NOT NULL,
  `acquirer_reference_number` varchar(50) NOT NULL,
  `seller_payable_breakdown` text,
  `id_order_slip` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_pscheckout_tracking`
--

DROP TABLE IF EXISTS `ps_pscheckout_tracking`;
CREATE TABLE IF NOT EXISTS `ps_pscheckout_tracking` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_order` int UNSIGNED NOT NULL,
  `tracking_number` varchar(64) NOT NULL,
  `carrier_id` int UNSIGNED NOT NULL,
  `carrier_name` varchar(64) NOT NULL,
  `paypal_order_id` varchar(50) NOT NULL,
  `paypal_capture_id` varchar(50) NOT NULL,
  `tracker_id` varchar(64) DEFAULT NULL,
  `items` text,
  `status` varchar(20) NOT NULL DEFAULT 'PENDING',
  `paypal_tracking_status` varchar(20) DEFAULT NULL,
  `payload_checksum` varchar(64) NOT NULL,
  `sent_to_paypal` tinyint(1) NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_order` (`id_order`),
  KEY `tracking_number` (`tracking_number`),
  KEY `paypal_order_id` (`paypal_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_psgdpr_consent`
--

DROP TABLE IF EXISTS `ps_psgdpr_consent`;
CREATE TABLE IF NOT EXISTS `ps_psgdpr_consent` (
  `id_gdpr_consent` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_module` int UNSIGNED NOT NULL,
  `active` int NOT NULL,
  `error` int DEFAULT NULL,
  `error_message` text,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_gdpr_consent`,`id_module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_psgdpr_consent_lang`
--

DROP TABLE IF EXISTS `ps_psgdpr_consent_lang`;
CREATE TABLE IF NOT EXISTS `ps_psgdpr_consent_lang` (
  `id_gdpr_consent` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_lang` int UNSIGNED NOT NULL,
  `message` text,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_gdpr_consent`,`id_lang`,`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_psgdpr_log`
--

DROP TABLE IF EXISTS `ps_psgdpr_log`;
CREATE TABLE IF NOT EXISTS `ps_psgdpr_log` (
  `id_gdpr_log` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_customer` int UNSIGNED DEFAULT NULL,
  `id_guest` int UNSIGNED DEFAULT NULL,
  `client_name` varchar(250) DEFAULT NULL,
  `id_module` int UNSIGNED NOT NULL,
  `request_type` int NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_gdpr_log`),
  KEY `id_customer` (`id_customer`),
  KEY `idx_id_customer` (`id_customer`,`id_guest`,`client_name`,`id_module`,`date_add`,`date_upd`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_psreassurance`
--

DROP TABLE IF EXISTS `ps_psreassurance`;
CREATE TABLE IF NOT EXISTS `ps_psreassurance` (
  `id_psreassurance` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `icon` varchar(255) DEFAULT NULL,
  `custom_icon` varchar(255) DEFAULT NULL,
  `status` int UNSIGNED NOT NULL,
  `position` int UNSIGNED NOT NULL,
  `type_link` int UNSIGNED DEFAULT NULL,
  `id_cms` int UNSIGNED DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime DEFAULT NULL,
  PRIMARY KEY (`id_psreassurance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_psreassurance_lang`
--

DROP TABLE IF EXISTS `ps_psreassurance_lang`;
CREATE TABLE IF NOT EXISTS `ps_psreassurance_lang` (
  `id_psreassurance` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id_psreassurance`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_quick_access`
--

DROP TABLE IF EXISTS `ps_quick_access`;
CREATE TABLE IF NOT EXISTS `ps_quick_access` (
  `id_quick_access` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `new_window` tinyint(1) NOT NULL DEFAULT '0',
  `link` varchar(255) NOT NULL,
  PRIMARY KEY (`id_quick_access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_quick_access_lang`
--

DROP TABLE IF EXISTS `ps_quick_access_lang`;
CREATE TABLE IF NOT EXISTS `ps_quick_access_lang` (
  `id_quick_access` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id_quick_access`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_range_price`
--

DROP TABLE IF EXISTS `ps_range_price`;
CREATE TABLE IF NOT EXISTS `ps_range_price` (
  `id_range_price` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_carrier` int UNSIGNED NOT NULL,
  `delimiter1` decimal(20,6) NOT NULL,
  `delimiter2` decimal(20,6) NOT NULL,
  PRIMARY KEY (`id_range_price`),
  UNIQUE KEY `id_carrier` (`id_carrier`,`delimiter1`,`delimiter2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_range_weight`
--

DROP TABLE IF EXISTS `ps_range_weight`;
CREATE TABLE IF NOT EXISTS `ps_range_weight` (
  `id_range_weight` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_carrier` int UNSIGNED NOT NULL,
  `delimiter1` decimal(20,6) NOT NULL,
  `delimiter2` decimal(20,6) NOT NULL,
  PRIMARY KEY (`id_range_weight`),
  UNIQUE KEY `id_carrier` (`id_carrier`,`delimiter1`,`delimiter2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_request_sql`
--

DROP TABLE IF EXISTS `ps_request_sql`;
CREATE TABLE IF NOT EXISTS `ps_request_sql` (
  `id_request_sql` int NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `sql` mediumtext NOT NULL,
  PRIMARY KEY (`id_request_sql`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_required_field`
--

DROP TABLE IF EXISTS `ps_required_field`;
CREATE TABLE IF NOT EXISTS `ps_required_field` (
  `id_required_field` int NOT NULL AUTO_INCREMENT,
  `object_name` varchar(32) NOT NULL,
  `field_name` varchar(32) NOT NULL,
  PRIMARY KEY (`id_required_field`),
  KEY `object_name` (`object_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_risk`
--

DROP TABLE IF EXISTS `ps_risk`;
CREATE TABLE IF NOT EXISTS `ps_risk` (
  `id_risk` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `percent` tinyint NOT NULL,
  `color` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id_risk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_risk_lang`
--

DROP TABLE IF EXISTS `ps_risk_lang`;
CREATE TABLE IF NOT EXISTS `ps_risk_lang` (
  `id_risk` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id_risk`,`id_lang`),
  KEY `id_risk` (`id_risk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_search_engine`
--

DROP TABLE IF EXISTS `ps_search_engine`;
CREATE TABLE IF NOT EXISTS `ps_search_engine` (
  `id_search_engine` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `server` varchar(64) NOT NULL,
  `getvar` varchar(16) NOT NULL,
  PRIMARY KEY (`id_search_engine`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_search_index`
--

DROP TABLE IF EXISTS `ps_search_index`;
CREATE TABLE IF NOT EXISTS `ps_search_index` (
  `id_product` int UNSIGNED NOT NULL,
  `id_word` int UNSIGNED NOT NULL,
  `weight` smallint UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_word`,`id_product`),
  KEY `id_product` (`id_product`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_search_word`
--

DROP TABLE IF EXISTS `ps_search_word`;
CREATE TABLE IF NOT EXISTS `ps_search_word` (
  `id_word` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_lang` int UNSIGNED NOT NULL,
  `word` varchar(30) NOT NULL,
  PRIMARY KEY (`id_word`),
  UNIQUE KEY `id_lang` (`id_lang`,`id_shop`,`word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_shop`
--

DROP TABLE IF EXISTS `ps_shop`;
CREATE TABLE IF NOT EXISTS `ps_shop` (
  `id_shop` int NOT NULL AUTO_INCREMENT,
  `id_shop_group` int NOT NULL,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_category` int NOT NULL,
  `theme_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_shop`),
  KEY `IDX_CBDFBB9EF5C9E40` (`id_shop_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_shop_group`
--

DROP TABLE IF EXISTS `ps_shop_group`;
CREATE TABLE IF NOT EXISTS `ps_shop_group` (
  `id_shop_group` int NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `share_customer` tinyint(1) NOT NULL,
  `share_order` tinyint(1) NOT NULL,
  `share_stock` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_shop_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_shop_url`
--

DROP TABLE IF EXISTS `ps_shop_url`;
CREATE TABLE IF NOT EXISTS `ps_shop_url` (
  `id_shop_url` int NOT NULL AUTO_INCREMENT,
  `id_shop` int NOT NULL,
  `domain` varchar(150) NOT NULL,
  `domain_ssl` varchar(150) NOT NULL,
  `physical_uri` varchar(64) NOT NULL,
  `virtual_uri` varchar(64) NOT NULL,
  `main` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_shop_url`),
  KEY `IDX_279F19DA274A50A0` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_smarty_cache`
--

DROP TABLE IF EXISTS `ps_smarty_cache`;
CREATE TABLE IF NOT EXISTS `ps_smarty_cache` (
  `id_smarty_cache` char(40) NOT NULL,
  `name` char(40) NOT NULL,
  `cache_id` varchar(254) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` longtext NOT NULL,
  PRIMARY KEY (`id_smarty_cache`),
  KEY `name` (`name`),
  KEY `cache_id` (`cache_id`),
  KEY `modified` (`modified`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_smarty_last_flush`
--

DROP TABLE IF EXISTS `ps_smarty_last_flush`;
CREATE TABLE IF NOT EXISTS `ps_smarty_last_flush` (
  `type` enum('compile','template') NOT NULL,
  `last_flush` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_smarty_lazy_cache`
--

DROP TABLE IF EXISTS `ps_smarty_lazy_cache`;
CREATE TABLE IF NOT EXISTS `ps_smarty_lazy_cache` (
  `template_hash` varchar(32) NOT NULL DEFAULT '',
  `cache_id` varchar(191) NOT NULL DEFAULT '',
  `compile_id` varchar(32) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL DEFAULT '',
  `last_update` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`template_hash`,`cache_id`,`compile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_specific_price`
--

DROP TABLE IF EXISTS `ps_specific_price`;
CREATE TABLE IF NOT EXISTS `ps_specific_price` (
  `id_specific_price` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_specific_price_rule` int UNSIGNED NOT NULL,
  `id_cart` int UNSIGNED NOT NULL,
  `id_product` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_shop_group` int UNSIGNED NOT NULL,
  `id_currency` int UNSIGNED NOT NULL,
  `id_country` int UNSIGNED NOT NULL,
  `id_group` int UNSIGNED NOT NULL,
  `id_customer` int UNSIGNED NOT NULL,
  `id_product_attribute` int UNSIGNED NOT NULL,
  `price` decimal(20,6) NOT NULL,
  `from_quantity` mediumint UNSIGNED NOT NULL,
  `reduction` decimal(20,6) NOT NULL,
  `reduction_tax` tinyint(1) NOT NULL DEFAULT '1',
  `reduction_type` enum('amount','percentage') NOT NULL,
  `from` datetime NOT NULL,
  `to` datetime NOT NULL,
  PRIMARY KEY (`id_specific_price`),
  UNIQUE KEY `id_product_2` (`id_product`,`id_product_attribute`,`id_customer`,`id_cart`,`from`,`to`,`id_shop`,`id_shop_group`,`id_currency`,`id_country`,`id_group`,`from_quantity`,`id_specific_price_rule`),
  KEY `id_product` (`id_product`,`id_shop`,`id_currency`,`id_country`,`id_group`,`id_customer`,`from_quantity`,`from`,`to`),
  KEY `from_quantity` (`from_quantity`),
  KEY `id_specific_price_rule` (`id_specific_price_rule`),
  KEY `id_cart` (`id_cart`),
  KEY `id_product_attribute` (`id_product_attribute`),
  KEY `id_shop` (`id_shop`),
  KEY `id_customer` (`id_customer`),
  KEY `from` (`from`),
  KEY `to` (`to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_specific_price_priority`
--

DROP TABLE IF EXISTS `ps_specific_price_priority`;
CREATE TABLE IF NOT EXISTS `ps_specific_price_priority` (
  `id_specific_price_priority` int NOT NULL AUTO_INCREMENT,
  `id_product` int NOT NULL,
  `priority` varchar(80) NOT NULL,
  PRIMARY KEY (`id_specific_price_priority`,`id_product`),
  UNIQUE KEY `id_product` (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_specific_price_rule`
--

DROP TABLE IF EXISTS `ps_specific_price_rule`;
CREATE TABLE IF NOT EXISTS `ps_specific_price_rule` (
  `id_specific_price_rule` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_currency` int UNSIGNED NOT NULL,
  `id_country` int UNSIGNED NOT NULL,
  `id_group` int UNSIGNED NOT NULL,
  `from_quantity` mediumint UNSIGNED NOT NULL,
  `price` decimal(20,6) DEFAULT NULL,
  `reduction` decimal(20,6) NOT NULL,
  `reduction_tax` tinyint(1) NOT NULL DEFAULT '1',
  `reduction_type` enum('amount','percentage') NOT NULL,
  `from` datetime NOT NULL,
  `to` datetime NOT NULL,
  PRIMARY KEY (`id_specific_price_rule`),
  KEY `id_product` (`id_shop`,`id_currency`,`id_country`,`id_group`,`from_quantity`,`from`,`to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_specific_price_rule_condition`
--

DROP TABLE IF EXISTS `ps_specific_price_rule_condition`;
CREATE TABLE IF NOT EXISTS `ps_specific_price_rule_condition` (
  `id_specific_price_rule_condition` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_specific_price_rule_condition_group` int UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id_specific_price_rule_condition`),
  KEY `id_specific_price_rule_condition_group` (`id_specific_price_rule_condition_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_specific_price_rule_condition_group`
--

DROP TABLE IF EXISTS `ps_specific_price_rule_condition_group`;
CREATE TABLE IF NOT EXISTS `ps_specific_price_rule_condition_group` (
  `id_specific_price_rule_condition_group` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_specific_price_rule` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_specific_price_rule_condition_group`,`id_specific_price_rule`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_state`
--

DROP TABLE IF EXISTS `ps_state`;
CREATE TABLE IF NOT EXISTS `ps_state` (
  `id_state` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_country` int UNSIGNED NOT NULL,
  `id_zone` int UNSIGNED NOT NULL,
  `name` varchar(80) NOT NULL,
  `iso_code` varchar(7) NOT NULL,
  `tax_behavior` smallint NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_state`),
  KEY `id_country` (`id_country`),
  KEY `name` (`name`),
  KEY `id_zone` (`id_zone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_statssearch`
--

DROP TABLE IF EXISTS `ps_statssearch`;
CREATE TABLE IF NOT EXISTS `ps_statssearch` (
  `id_statssearch` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_shop` int UNSIGNED NOT NULL DEFAULT '1',
  `id_shop_group` int UNSIGNED NOT NULL DEFAULT '1',
  `keywords` varchar(255) NOT NULL,
  `results` int NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_statssearch`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_stock`
--

DROP TABLE IF EXISTS `ps_stock`;
CREATE TABLE IF NOT EXISTS `ps_stock` (
  `id_stock` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_warehouse` int UNSIGNED NOT NULL,
  `id_product` int UNSIGNED NOT NULL,
  `id_product_attribute` int UNSIGNED NOT NULL,
  `reference` varchar(64) NOT NULL,
  `ean13` varchar(13) DEFAULT NULL,
  `isbn` varchar(32) DEFAULT NULL,
  `upc` varchar(12) DEFAULT NULL,
  `mpn` varchar(40) DEFAULT NULL,
  `physical_quantity` int UNSIGNED NOT NULL,
  `usable_quantity` int UNSIGNED NOT NULL,
  `price_te` decimal(20,6) DEFAULT '0.000000',
  PRIMARY KEY (`id_stock`),
  KEY `id_warehouse` (`id_warehouse`),
  KEY `id_product` (`id_product`),
  KEY `id_product_attribute` (`id_product_attribute`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_stock_available`
--

DROP TABLE IF EXISTS `ps_stock_available`;
CREATE TABLE IF NOT EXISTS `ps_stock_available` (
  `id_stock_available` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product` int UNSIGNED NOT NULL,
  `id_product_attribute` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  `id_shop_group` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `physical_quantity` int NOT NULL DEFAULT '0',
  `reserved_quantity` int NOT NULL DEFAULT '0',
  `depends_on_stock` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `out_of_stock` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `location` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_stock_available`),
  UNIQUE KEY `product_sqlstock` (`id_product`,`id_product_attribute`,`id_shop`,`id_shop_group`),
  KEY `id_shop` (`id_shop`),
  KEY `id_shop_group` (`id_shop_group`),
  KEY `id_product` (`id_product`),
  KEY `id_product_attribute` (`id_product_attribute`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_stock_mvt`
--

DROP TABLE IF EXISTS `ps_stock_mvt`;
CREATE TABLE IF NOT EXISTS `ps_stock_mvt` (
  `id_stock_mvt` bigint NOT NULL AUTO_INCREMENT,
  `id_stock` int NOT NULL,
  `id_order` int DEFAULT NULL,
  `id_supply_order` int DEFAULT NULL,
  `id_stock_mvt_reason` int NOT NULL,
  `id_employee` int NOT NULL,
  `employee_lastname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_firstname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `physical_quantity` int UNSIGNED NOT NULL,
  `date_add` datetime NOT NULL,
  `sign` smallint NOT NULL DEFAULT '1',
  `price_te` decimal(20,6) DEFAULT '0.000000',
  `last_wa` decimal(20,6) DEFAULT '0.000000',
  `current_wa` decimal(20,6) DEFAULT '0.000000',
  `referer` bigint DEFAULT NULL,
  PRIMARY KEY (`id_stock_mvt`),
  KEY `id_stock` (`id_stock`),
  KEY `id_stock_mvt_reason` (`id_stock_mvt_reason`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_stock_mvt_reason`
--

DROP TABLE IF EXISTS `ps_stock_mvt_reason`;
CREATE TABLE IF NOT EXISTS `ps_stock_mvt_reason` (
  `id_stock_mvt_reason` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `sign` tinyint(1) NOT NULL DEFAULT '1',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `deleted` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_stock_mvt_reason`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_stock_mvt_reason_lang`
--

DROP TABLE IF EXISTS `ps_stock_mvt_reason_lang`;
CREATE TABLE IF NOT EXISTS `ps_stock_mvt_reason_lang` (
  `id_stock_mvt_reason` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`id_stock_mvt_reason`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_store`
--

DROP TABLE IF EXISTS `ps_store`;
CREATE TABLE IF NOT EXISTS `ps_store` (
  `id_store` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_country` int UNSIGNED NOT NULL,
  `id_state` int UNSIGNED DEFAULT NULL,
  `city` varchar(64) NOT NULL,
  `postcode` varchar(12) NOT NULL,
  `latitude` decimal(13,8) DEFAULT NULL,
  `longitude` decimal(13,8) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `fax` varchar(16) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_store`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_store_lang`
--

DROP TABLE IF EXISTS `ps_store_lang`;
CREATE TABLE IF NOT EXISTS `ps_store_lang` (
  `id_store` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `hours` mediumtext,
  `note` mediumtext,
  PRIMARY KEY (`id_store`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_store_shop`
--

DROP TABLE IF EXISTS `ps_store_shop`;
CREATE TABLE IF NOT EXISTS `ps_store_shop` (
  `id_store` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_store`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_supplier`
--

DROP TABLE IF EXISTS `ps_supplier`;
CREATE TABLE IF NOT EXISTS `ps_supplier` (
  `id_supplier` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_supplier_lang`
--

DROP TABLE IF EXISTS `ps_supplier_lang`;
CREATE TABLE IF NOT EXISTS `ps_supplier_lang` (
  `id_supplier` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `description` mediumtext,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id_supplier`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_supplier_shop`
--

DROP TABLE IF EXISTS `ps_supplier_shop`;
CREATE TABLE IF NOT EXISTS `ps_supplier_shop` (
  `id_supplier` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_supplier`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_supply_order`
--

DROP TABLE IF EXISTS `ps_supply_order`;
CREATE TABLE IF NOT EXISTS `ps_supply_order` (
  `id_supply_order` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_supplier` int UNSIGNED NOT NULL,
  `supplier_name` varchar(64) NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `id_warehouse` int UNSIGNED NOT NULL,
  `id_supply_order_state` int UNSIGNED NOT NULL,
  `id_currency` int UNSIGNED NOT NULL,
  `id_ref_currency` int UNSIGNED NOT NULL,
  `reference` varchar(64) NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `date_delivery_expected` datetime DEFAULT NULL,
  `total_te` decimal(20,6) DEFAULT '0.000000',
  `total_with_discount_te` decimal(20,6) DEFAULT '0.000000',
  `total_tax` decimal(20,6) DEFAULT '0.000000',
  `total_ti` decimal(20,6) DEFAULT '0.000000',
  `discount_rate` decimal(20,6) DEFAULT '0.000000',
  `discount_value_te` decimal(20,6) DEFAULT '0.000000',
  `is_template` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id_supply_order`),
  KEY `id_supplier` (`id_supplier`),
  KEY `id_warehouse` (`id_warehouse`),
  KEY `reference` (`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_supply_order_detail`
--

DROP TABLE IF EXISTS `ps_supply_order_detail`;
CREATE TABLE IF NOT EXISTS `ps_supply_order_detail` (
  `id_supply_order_detail` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_supply_order` int UNSIGNED NOT NULL,
  `id_currency` int UNSIGNED NOT NULL,
  `id_product` int UNSIGNED NOT NULL,
  `id_product_attribute` int UNSIGNED NOT NULL,
  `reference` varchar(64) NOT NULL,
  `supplier_reference` varchar(64) NOT NULL,
  `name` varchar(128) NOT NULL,
  `ean13` varchar(13) DEFAULT NULL,
  `isbn` varchar(32) DEFAULT NULL,
  `upc` varchar(12) DEFAULT NULL,
  `mpn` varchar(40) DEFAULT NULL,
  `exchange_rate` decimal(20,6) DEFAULT '0.000000',
  `unit_price_te` decimal(20,6) DEFAULT '0.000000',
  `quantity_expected` int UNSIGNED NOT NULL,
  `quantity_received` int UNSIGNED NOT NULL,
  `price_te` decimal(20,6) DEFAULT '0.000000',
  `discount_rate` decimal(20,6) DEFAULT '0.000000',
  `discount_value_te` decimal(20,6) DEFAULT '0.000000',
  `price_with_discount_te` decimal(20,6) DEFAULT '0.000000',
  `tax_rate` decimal(20,6) DEFAULT '0.000000',
  `tax_value` decimal(20,6) DEFAULT '0.000000',
  `price_ti` decimal(20,6) DEFAULT '0.000000',
  `tax_value_with_order_discount` decimal(20,6) DEFAULT '0.000000',
  `price_with_order_discount_te` decimal(20,6) DEFAULT '0.000000',
  PRIMARY KEY (`id_supply_order_detail`),
  KEY `id_supply_order` (`id_supply_order`,`id_product`),
  KEY `id_product_attribute` (`id_product_attribute`),
  KEY `id_product_product_attribute` (`id_product`,`id_product_attribute`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_supply_order_history`
--

DROP TABLE IF EXISTS `ps_supply_order_history`;
CREATE TABLE IF NOT EXISTS `ps_supply_order_history` (
  `id_supply_order_history` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_supply_order` int UNSIGNED NOT NULL,
  `id_employee` int UNSIGNED NOT NULL,
  `employee_lastname` varchar(255) DEFAULT '',
  `employee_firstname` varchar(255) DEFAULT '',
  `id_state` int UNSIGNED NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_supply_order_history`),
  KEY `id_supply_order` (`id_supply_order`),
  KEY `id_employee` (`id_employee`),
  KEY `id_state` (`id_state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_supply_order_receipt_history`
--

DROP TABLE IF EXISTS `ps_supply_order_receipt_history`;
CREATE TABLE IF NOT EXISTS `ps_supply_order_receipt_history` (
  `id_supply_order_receipt_history` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_supply_order_detail` int UNSIGNED NOT NULL,
  `id_employee` int UNSIGNED NOT NULL,
  `employee_lastname` varchar(255) DEFAULT '',
  `employee_firstname` varchar(255) DEFAULT '',
  `id_supply_order_state` int UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id_supply_order_receipt_history`),
  KEY `id_supply_order_detail` (`id_supply_order_detail`),
  KEY `id_supply_order_state` (`id_supply_order_state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_supply_order_state`
--

DROP TABLE IF EXISTS `ps_supply_order_state`;
CREATE TABLE IF NOT EXISTS `ps_supply_order_state` (
  `id_supply_order_state` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `delivery_note` tinyint(1) NOT NULL DEFAULT '0',
  `editable` tinyint(1) NOT NULL DEFAULT '0',
  `receipt_state` tinyint(1) NOT NULL DEFAULT '0',
  `pending_receipt` tinyint(1) NOT NULL DEFAULT '0',
  `enclosed` tinyint(1) NOT NULL DEFAULT '0',
  `color` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id_supply_order_state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_supply_order_state_lang`
--

DROP TABLE IF EXISTS `ps_supply_order_state_lang`;
CREATE TABLE IF NOT EXISTS `ps_supply_order_state_lang` (
  `id_supply_order_state` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id_supply_order_state`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_tab`
--

DROP TABLE IF EXISTS `ps_tab`;
CREATE TABLE IF NOT EXISTS `ps_tab` (
  `id_tab` int NOT NULL AUTO_INCREMENT,
  `id_parent` int NOT NULL,
  `position` int NOT NULL,
  `module` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `class_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `icon` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wording` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wording_domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_tab`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_tab_advice`
--

DROP TABLE IF EXISTS `ps_tab_advice`;
CREATE TABLE IF NOT EXISTS `ps_tab_advice` (
  `id_tab` int NOT NULL,
  `id_advice` int NOT NULL,
  PRIMARY KEY (`id_tab`,`id_advice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_tab_lang`
--

DROP TABLE IF EXISTS `ps_tab_lang`;
CREATE TABLE IF NOT EXISTS `ps_tab_lang` (
  `id_tab` int NOT NULL,
  `id_lang` int NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_tab`,`id_lang`),
  KEY `IDX_CFD9262DED47AB56` (`id_tab`),
  KEY `IDX_CFD9262DBA299860` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_tab_module_preference`
--

DROP TABLE IF EXISTS `ps_tab_module_preference`;
CREATE TABLE IF NOT EXISTS `ps_tab_module_preference` (
  `id_tab_module_preference` int NOT NULL AUTO_INCREMENT,
  `id_employee` int NOT NULL,
  `id_tab` int NOT NULL,
  `module` varchar(191) NOT NULL,
  PRIMARY KEY (`id_tab_module_preference`),
  UNIQUE KEY `employee_module` (`id_employee`,`id_tab`,`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_tag`
--

DROP TABLE IF EXISTS `ps_tag`;
CREATE TABLE IF NOT EXISTS `ps_tag` (
  `id_tag` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id_tag`),
  KEY `tag_name` (`name`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_tag_count`
--

DROP TABLE IF EXISTS `ps_tag_count`;
CREATE TABLE IF NOT EXISTS `ps_tag_count` (
  `id_group` int UNSIGNED NOT NULL DEFAULT '0',
  `id_tag` int UNSIGNED NOT NULL DEFAULT '0',
  `id_lang` int UNSIGNED NOT NULL DEFAULT '0',
  `id_shop` int UNSIGNED NOT NULL DEFAULT '0',
  `counter` int UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_group`,`id_tag`),
  KEY `id_group` (`id_group`,`id_lang`,`id_shop`,`counter`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_tax`
--

DROP TABLE IF EXISTS `ps_tax`;
CREATE TABLE IF NOT EXISTS `ps_tax` (
  `id_tax` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `rate` decimal(10,3) NOT NULL,
  `active` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `deleted` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_tax`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_tax_lang`
--

DROP TABLE IF EXISTS `ps_tax_lang`;
CREATE TABLE IF NOT EXISTS `ps_tax_lang` (
  `id_tax` int UNSIGNED NOT NULL,
  `id_lang` int UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id_tax`,`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_tax_rule`
--

DROP TABLE IF EXISTS `ps_tax_rule`;
CREATE TABLE IF NOT EXISTS `ps_tax_rule` (
  `id_tax_rule` int NOT NULL AUTO_INCREMENT,
  `id_tax_rules_group` int NOT NULL,
  `id_country` int NOT NULL,
  `id_state` int NOT NULL,
  `zipcode_from` varchar(12) NOT NULL,
  `zipcode_to` varchar(12) NOT NULL,
  `id_tax` int NOT NULL,
  `behavior` int NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id_tax_rule`),
  KEY `id_tax_rules_group` (`id_tax_rules_group`),
  KEY `id_tax` (`id_tax`),
  KEY `category_getproducts` (`id_tax_rules_group`,`id_country`,`id_state`,`zipcode_from`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_tax_rules_group`
--

DROP TABLE IF EXISTS `ps_tax_rules_group`;
CREATE TABLE IF NOT EXISTS `ps_tax_rules_group` (
  `id_tax_rules_group` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `active` int NOT NULL,
  `deleted` tinyint UNSIGNED NOT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (`id_tax_rules_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_tax_rules_group_shop`
--

DROP TABLE IF EXISTS `ps_tax_rules_group_shop`;
CREATE TABLE IF NOT EXISTS `ps_tax_rules_group_shop` (
  `id_tax_rules_group` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_tax_rules_group`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_timezone`
--

DROP TABLE IF EXISTS `ps_timezone`;
CREATE TABLE IF NOT EXISTS `ps_timezone` (
  `id_timezone` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  PRIMARY KEY (`id_timezone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_translation`
--

DROP TABLE IF EXISTS `ps_translation`;
CREATE TABLE IF NOT EXISTS `ps_translation` (
  `id_translation` int NOT NULL AUTO_INCREMENT,
  `id_lang` int NOT NULL,
  `key` text CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `translation` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `theme` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_translation`),
  KEY `IDX_ADEBEB36BA299860` (`id_lang`),
  KEY `key` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_warehouse`
--

DROP TABLE IF EXISTS `ps_warehouse`;
CREATE TABLE IF NOT EXISTS `ps_warehouse` (
  `id_warehouse` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_currency` int UNSIGNED NOT NULL,
  `id_address` int UNSIGNED NOT NULL,
  `id_employee` int UNSIGNED NOT NULL,
  `reference` varchar(64) DEFAULT NULL,
  `name` varchar(45) NOT NULL,
  `management_type` enum('WA','FIFO','LIFO') NOT NULL DEFAULT 'WA',
  `deleted` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_warehouse`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_warehouse_carrier`
--

DROP TABLE IF EXISTS `ps_warehouse_carrier`;
CREATE TABLE IF NOT EXISTS `ps_warehouse_carrier` (
  `id_carrier` int UNSIGNED NOT NULL,
  `id_warehouse` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_warehouse`,`id_carrier`),
  KEY `id_warehouse` (`id_warehouse`),
  KEY `id_carrier` (`id_carrier`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_warehouse_product_location`
--

DROP TABLE IF EXISTS `ps_warehouse_product_location`;
CREATE TABLE IF NOT EXISTS `ps_warehouse_product_location` (
  `id_warehouse_product_location` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_product` int UNSIGNED NOT NULL,
  `id_product_attribute` int UNSIGNED NOT NULL,
  `id_warehouse` int UNSIGNED NOT NULL,
  `location` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id_warehouse_product_location`),
  UNIQUE KEY `id_product` (`id_product`,`id_product_attribute`,`id_warehouse`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_warehouse_shop`
--

DROP TABLE IF EXISTS `ps_warehouse_shop`;
CREATE TABLE IF NOT EXISTS `ps_warehouse_shop` (
  `id_shop` int UNSIGNED NOT NULL,
  `id_warehouse` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_warehouse`,`id_shop`),
  KEY `id_warehouse` (`id_warehouse`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_webservice_account`
--

DROP TABLE IF EXISTS `ps_webservice_account`;
CREATE TABLE IF NOT EXISTS `ps_webservice_account` (
  `id_webservice_account` int NOT NULL AUTO_INCREMENT,
  `key` varchar(32) NOT NULL,
  `description` mediumtext,
  `class_name` varchar(50) NOT NULL DEFAULT 'WebserviceRequest',
  `is_module` tinyint NOT NULL DEFAULT '0',
  `module_name` varchar(50) DEFAULT NULL,
  `active` tinyint NOT NULL,
  PRIMARY KEY (`id_webservice_account`),
  KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_webservice_account_shop`
--

DROP TABLE IF EXISTS `ps_webservice_account_shop`;
CREATE TABLE IF NOT EXISTS `ps_webservice_account_shop` (
  `id_webservice_account` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_webservice_account`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_webservice_permission`
--

DROP TABLE IF EXISTS `ps_webservice_permission`;
CREATE TABLE IF NOT EXISTS `ps_webservice_permission` (
  `id_webservice_permission` int NOT NULL AUTO_INCREMENT,
  `resource` varchar(50) NOT NULL,
  `method` enum('GET','POST','PUT','PATCH','DELETE','HEAD') NOT NULL,
  `id_webservice_account` int NOT NULL,
  PRIMARY KEY (`id_webservice_permission`),
  UNIQUE KEY `resource_2` (`resource`,`method`,`id_webservice_account`),
  KEY `resource` (`resource`),
  KEY `method` (`method`),
  KEY `id_webservice_account` (`id_webservice_account`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_web_browser`
--

DROP TABLE IF EXISTS `ps_web_browser`;
CREATE TABLE IF NOT EXISTS `ps_web_browser` (
  `id_web_browser` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id_web_browser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_wishlist`
--

DROP TABLE IF EXISTS `ps_wishlist`;
CREATE TABLE IF NOT EXISTS `ps_wishlist` (
  `id_wishlist` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_customer` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED DEFAULT '1',
  `id_shop_group` int UNSIGNED DEFAULT '1',
  `token` varchar(64) NOT NULL,
  `name` varchar(64) NOT NULL,
  `counter` int UNSIGNED DEFAULT NULL,
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  `default` int UNSIGNED DEFAULT '0',
  PRIMARY KEY (`id_wishlist`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_wishlist_product`
--

DROP TABLE IF EXISTS `ps_wishlist_product`;
CREATE TABLE IF NOT EXISTS `ps_wishlist_product` (
  `id_wishlist_product` int NOT NULL AUTO_INCREMENT,
  `id_wishlist` int UNSIGNED NOT NULL,
  `id_product` int UNSIGNED NOT NULL,
  `id_product_attribute` int UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `priority` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_wishlist_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_wishlist_product_cart`
--

DROP TABLE IF EXISTS `ps_wishlist_product_cart`;
CREATE TABLE IF NOT EXISTS `ps_wishlist_product_cart` (
  `id_wishlist_product` int UNSIGNED NOT NULL,
  `id_cart` int UNSIGNED NOT NULL,
  `quantity` int UNSIGNED NOT NULL,
  `date_add` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `ps_zone`
--

DROP TABLE IF EXISTS `ps_zone`;
CREATE TABLE IF NOT EXISTS `ps_zone` (
  `id_zone` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `active` tinyint UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_zone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ps_zone_shop`
--

DROP TABLE IF EXISTS `ps_zone_shop`;
CREATE TABLE IF NOT EXISTS `ps_zone_shop` (
  `id_zone` int UNSIGNED NOT NULL,
  `id_shop` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id_zone`,`id_shop`),
  KEY `id_shop` (`id_shop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
