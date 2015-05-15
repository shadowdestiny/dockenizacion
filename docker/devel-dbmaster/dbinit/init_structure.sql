-- phpMyAdmin SQL Dump
-- version 2.6.2-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Apr 10, 2015 at 04:23 PM
-- Server version: 1.0.16
-- PHP Version: 5.6.7-1
-- 
-- Database: `euromillions`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `acl_resource_roles`
-- 

CREATE TABLE IF NOT EXISTS `acl_resource_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `resource_id` int(10) unsigned NOT NULL,
  `value` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `acl_resource_users`
--

CREATE TABLE IF NOT EXISTS `acl_resource_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `resource_id` int(10) unsigned NOT NULL,
  `value` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`resource_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `acl_resources`
--

CREATE TABLE IF NOT EXISTS `acl_resources` (
  `resource_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('frontend','backend') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'frontend',
  `category` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `default_value` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`resource_id`),
  UNIQUE KEY `name` (`name`),
  KEY `category` (`category`),
  KEY `type` (`type`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `acl_roles`
--

CREATE TABLE IF NOT EXISTS `acl_roles` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `article_details`
--

CREATE TABLE IF NOT EXISTS `article_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(10) unsigned NOT NULL,
  `lang` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `alias` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `published_on` int(10) unsigned NOT NULL,
  `published_by` int(10) unsigned NOT NULL,
  `published_by_name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `key_orig` text COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header` text COLLATE utf8_unicode_ci,
  `alt` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `creation_date` int(10) unsigned NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `change_date` int(10) unsigned NOT NULL,
  `changed_by` int(10) unsigned NOT NULL,
  `count_view` int(10) unsigned NOT NULL,
  `last_view` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`article_id`,`lang`),
  KEY `lang` (`lang`),
  KEY `alias_2` (`alias`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `article_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creation_date` int(10) unsigned NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `published_by_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `published_by` int(10) unsigned NOT NULL,
  `published_on` int(10) unsigned NOT NULL,
  `count_view` int(10) unsigned NOT NULL,
  `last_view` int(10) unsigned NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`article_id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `cart_data`
--

CREATE TABLE IF NOT EXISTS `cart_data` (
  `cart_data_id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(10) unsigned NOT NULL,
  `lottery` enum('euromillions') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'euromillions',
  `post_id` int(10) unsigned NOT NULL,
  `add_date` datetime NOT NULL,
  `start_draw_date` datetime NOT NULL,
  `price_total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cart_data_id`),
  KEY `cart_id` (`cart_id`),
  KEY `lottery` (`lottery`),
  KEY `post_id` (`post_id`),
  KEY `add_date` (`add_date`),
  KEY `draw_date` (`start_draw_date`),
  KEY `cart_id_2` (`cart_id`,`lottery`,`post_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `cart_data_lc`
--

CREATE TABLE IF NOT EXISTS `cart_data_lc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cart_data_id` int(10) unsigned NOT NULL,
  `add_date` datetime NOT NULL,
  `draw_date` datetime NOT NULL,
  `draw_id` int(11) NOT NULL,
  `ticket_type_id` int(10) unsigned NOT NULL,
  `abo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tuesday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `friday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `num_draws` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `numbers` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stars` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,2) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `draw_date` (`draw_date`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE IF NOT EXISTS `carts` (
  `cart_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `guest_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` int(10) unsigned NOT NULL DEFAULT '1',
  `state` enum('open','goto_billing','billed','in_progress','done') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'open',
  `creation_date` datetime NOT NULL,
  `last_update` datetime NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`,`guest_id`),
  KEY `guest_id` (`guest_id`),
  KEY `last_update` (`last_update`),
  KEY `customer_id` (`customer_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `country_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active_registration` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `active_payout` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `short_code` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iban_mandatory` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `iban_example` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`country_id`),
  UNIQUE KEY `short_code` (`short_code`),
  UNIQUE KEY `name` (`name`),
  KEY `active_registration` (`active_registration`),
  KEY `active_payout` (`active_payout`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `currency` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `default_locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `format_pattern` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `rate` decimal(12,6) NOT NULL,
  `last_update` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `currency` (`currency`),
  KEY `active` (`active`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `domain` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mail_from_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mail_from` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `em_tickets`
--

CREATE TABLE IF NOT EXISTS `em_tickets` (
  `em_ticket_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'singleticket',
  `order_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `added_on` int(10) unsigned NOT NULL,
  `numbers` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stars` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tuesday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `friday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tuesday_friday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `num_draws` int(10) unsigned NOT NULL,
  `recuring` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `start_draw_id` int(10) unsigned NOT NULL,
  `start_date` datetime NOT NULL,
  `end_draw_id` int(10) unsigned NOT NULL,
  `end_date` datetime NOT NULL,
  `status` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  PRIMARY KEY (`em_ticket_id`),
  KEY `type` (`type`),
  KEY `customer_id` (`customer_id`),
  KEY `user_id` (`user_id`),
  KEY `start_draw_id` (`start_draw_id`),
  KEY `end_draw_id` (`end_draw_id`),
  KEY `end_date` (`end_date`),
  KEY `order_id` (`order_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `faq_data`
--

CREATE TABLE IF NOT EXISTS `faq_data` (
  `faq_data_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `faq_id` int(10) unsigned NOT NULL,
  `lang` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `answer` text COLLATE utf8_unicode_ci NOT NULL,
  `change_date` datetime NOT NULL,
  `changed_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`faq_data_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE IF NOT EXISTS `faqs` (
  `faq_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(4) unsigned NOT NULL DEFAULT '1',
  `pos` int(10) unsigned NOT NULL,
  `category` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'basic',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `change_date` datetime NOT NULL,
  `changed_by` int(10) unsigned NOT NULL,
  PRIMARY KEY (`faq_id`),
  UNIQUE KEY `name` (`name`),
  KEY `active` (`active`),
  KEY `pos` (`pos`),
  KEY `category` (`category`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ccode` varchar(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ccode` (`ccode`),
  INDEX (ccode)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lc_log`
--

CREATE TABLE IF NOT EXISTS `lc_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `method` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `customer_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `transaction_start_date` datetime NOT NULL,
  `transaction_end_date` datetime NOT NULL,
  `idsesion` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `start_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `start_key` tinyint(4) NOT NULL DEFAULT '0',
  `start_type` tinyint(4) NOT NULL DEFAULT '0',
  `lc_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ticket_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `status` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_id` int(10) unsigned NOT NULL DEFAULT '0',
  `em_ticket_id` int(10) unsigned NOT NULL DEFAULT '0',
  `draw_date` datetime NOT NULL,
  `numbers` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `stars` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `method` (`method`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `lc_tickets`
--

CREATE TABLE IF NOT EXISTS `lc_tickets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `em_ticket_id` int(10) unsigned NOT NULL,
  `lc_ticket_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `creation_date` datetime NOT NULL,
  `draw_date` date NOT NULL,
  `number1` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `number2` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `number3` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `number4` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `number5` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `number6` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `number7` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `number8` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `number9` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `number10` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `star1` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `star2` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `star3` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `star4` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `star5` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `state` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `won` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `prize` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `em_ticket_id` (`em_ticket_id`),
  KEY `lc_ticket_id` (`lc_ticket_id`),
  KEY `user_id` (`user_id`),
  KEY `creation_date` (`creation_date`),
  KEY `draw_date` (`draw_date`),
  KEY `number1` (`number1`),
  KEY `number2` (`number2`),
  KEY `number3` (`number3`),
  KEY `number4` (`number4`),
  KEY `number5` (`number5`),
  KEY `number6` (`number6`),
  KEY `number7` (`number7`),
  KEY `number8` (`number8`),
  KEY `number9` (`number9`),
  KEY `number10` (`number10`),
  KEY `star1` (`star1`),
  KEY `star2` (`star2`),
  KEY `star3` (`star3`),
  KEY `star4` (`star4`),
  KEY `star5` (`star5`),
  KEY `state` (`state`),
  KEY `won` (`won`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `log_system`
--

CREATE TABLE IF NOT EXISTS `log_system` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priority` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned DEFAULT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `user_agent` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `referer` text COLLATE utf8_unicode_ci,
  `uri` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `get_vars` text COLLATE utf8_unicode_ci NOT NULL,
  `post_vars` text COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(24) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `lottery_draws`
--

CREATE TABLE IF NOT EXISTS `lottery_draws` (
  `draw_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `draw_date` date NOT NULL,
  `jackpot` bigint(20) unsigned NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `big_winner` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`draw_id`),
  KEY `draw_date` (`draw_date`),
  KEY `published` (`published`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `lottery_results`
--

CREATE TABLE IF NOT EXISTS `lottery_results` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `draw_id` int(10) unsigned NOT NULL,
  `type` enum('standard','luckystar') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'standard',
  `pos` tinyint(3) unsigned NOT NULL,
  `number` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `draw_id` (`draw_id`,`type`,`pos`),
  KEY `number` (`number`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `lottery_winners`
--

CREATE TABLE IF NOT EXISTS `lottery_winners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `draw_id` int(10) unsigned NOT NULL,
  `numbers` tinyint(3) unsigned NOT NULL,
  `luckystars` tinyint(3) unsigned NOT NULL,
  `prize` decimal(20,2) unsigned NOT NULL,
  `winners` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `draw_id` (`draw_id`,`numbers`,`luckystars`),
  KEY `draw_id_2` (`draw_id`),
  KEY `winners` (`winners`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `mail_queue`
--

CREATE TABLE IF NOT EXISTS `mail_queue` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `priority` tinyint(3) unsigned NOT NULL DEFAULT '2',
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `to` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('new','starting_php','starting_nodejs','sending','error','done') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `added_on` datetime NOT NULL,
  `count_error` int(10) unsigned NOT NULL,
  `last_try` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `priority` (`priority`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `news_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creation_date` int(10) unsigned NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `published_by_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `published_by` int(10) unsigned NOT NULL,
  `published_on` int(10) unsigned NOT NULL,
  `count_view` int(10) unsigned NOT NULL,
  `last_view` int(10) unsigned NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`news_id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `news_details`
--

CREATE TABLE IF NOT EXISTS `news_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int(10) unsigned NOT NULL,
  `lang` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `alias` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `page_title` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `meta_keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '0',
  `published_on` int(10) unsigned NOT NULL,
  `published_by` int(10) unsigned NOT NULL,
  `published_by_name` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `header` text COLLATE utf8_unicode_ci,
  `alt` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  `creation_date` int(10) unsigned NOT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `change_date` int(10) unsigned NOT NULL,
  `changed_by` int(10) unsigned NOT NULL,
  `count_view` int(10) unsigned NOT NULL,
  `last_view` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`news_id`,`lang`),
  KEY `lang` (`lang`),
  KEY `alias_2` (`alias`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `order_data`
--

CREATE TABLE IF NOT EXISTS `order_data` (
  `order_data_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `lottery` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `post_id` int(10) unsigned NOT NULL,
  `add_date` datetime NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `start_draw_date` datetime NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `state` enum('new','done','in_progress','error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  PRIMARY KEY (`order_data_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `order_data_lc`
--

CREATE TABLE IF NOT EXISTS `order_data_lc` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_data_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `add_date` datetime NOT NULL,
  `draw_date` date NOT NULL,
  `draw_id` int(11) NOT NULL,
  `abo` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `tuesday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `friday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `num_draws` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `numbers` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stars` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` enum('new','done','in_progress','error','prepared_to)buy') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `state` (`state`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `state` enum('new','in_progress','done','error') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `order_date` datetime NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `transaction_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `product_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `category` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `product_type_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`product_id`),
  KEY `active` (`active`),
  KEY `type_id` (`product_type_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned DEFAULT NULL,
  `role` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'guest',
  `session_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_action` int(10) unsigned NOT NULL,
  `count_action` int(10) unsigned NOT NULL,
  `current_uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `host` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `stats_data`
--

CREATE TABLE IF NOT EXISTS `stats_data` (
  `year` mediumint(8) unsigned NOT NULL,
  `month` tinyint(3) unsigned NOT NULL,
  `day` tinyint(3) unsigned NOT NULL,
  `hour` tinyint(3) unsigned NOT NULL,
  `stats_type_id` mediumint(8) unsigned NOT NULL,
  `customer_id` mediumint(8) unsigned NOT NULL,
  `value` int(10) unsigned NOT NULL,
  PRIMARY KEY (`year`,`month`,`day`,`hour`,`stats_type_id`,`customer_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stats_tickets_lc`
--

CREATE TABLE IF NOT EXISTS `stats_tickets_lc` (
  `draw_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  `product_id` int(10) unsigned NOT NULL,
  `add_to_cart` int(10) unsigned NOT NULL,
  `remove_from_cart` int(10) unsigned NOT NULL,
  `validation_error` int(10) unsigned NOT NULL,
  `validation_ok` int(10) unsigned NOT NULL,
  `bought` int(10) unsigned NOT NULL,
  `bought_total_amount` decimal(10,2) unsigned NOT NULL,
  `won` int(10) unsigned NOT NULL,
  `won_total_amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`draw_id`,`customer_id`,`product_id`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stats_types`
--

CREATE TABLE IF NOT EXISTS `stats_types` (
  `stats_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`stats_type_id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `stats_user_online`
--

CREATE TABLE IF NOT EXISTS `stats_user_online` (
  `year` mediumint(4) unsigned NOT NULL,
  `month` tinyint(2) unsigned NOT NULL,
  `day` tinyint(2) unsigned NOT NULL,
  `country` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `gender_m` int(10) unsigned NOT NULL,
  `gender_f` int(10) unsigned NOT NULL,
  `gender_n` int(10) unsigned NOT NULL,
  PRIMARY KEY (`year`,`month`,`day`,`country`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_types`
--

CREATE TABLE IF NOT EXISTS `ticket_types` (
  `ticket_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `numbers` tinyint(3) unsigned NOT NULL,
  `stars` tinyint(3) unsigned NOT NULL,
  `price` decimal(10,2) unsigned NOT NULL,
  PRIMARY KEY (`ticket_type_id`),
  UNIQUE KEY `numbers` (`numbers`,`stars`),
  KEY `active` (`active`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Ticket types and there details'  ;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `transaction_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) unsigned NOT NULL DEFAULT '1',
  `transaction_date` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `transaction_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order_id` int(10) unsigned DEFAULT '0',
  `payout_id` int(10) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `biller` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `biller_transaction_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `user_id` (`user_id`),
  KEY `transaction_date` (`transaction_date`),
  KEY `customer_id` (`customer_id`),
  KEY `order_id` (`order_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `translation_details`
--

CREATE TABLE IF NOT EXISTS `translation_details` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `translation_id` int(10) unsigned NOT NULL,
  `lang` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `language_id` INT unsigned NULL,
  PRIMARY KEY (`id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `translation_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(254) COLLATE utf8_unicode_ci NOT NULL,
  `used` tinyint(3) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`translation_id`),
  UNIQUE KEY `key` (`key`),
  KEY `used` (`used`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE IF NOT EXISTS `user_details` (
  `user_id` int(10) unsigned NOT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nickname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `gender` enum('n','f','m') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'n',
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'DE',
  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `birth_date` date NOT NULL,
  `terms` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `newsletter` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `jackpot` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `country` (`country`),
  KEY `gender` (`gender`),
  KEY `tam` (`terms`),
  KEY `newsletter` (`newsletter`,`jackpot`)
) ENGINE=INNODB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_payout_bank_accounts`
--

CREATE TABLE IF NOT EXISTS `user_payout_bank_accounts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `state` enum('active','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
  `country` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `account_holder` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `bic` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `iban` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bic` (`bic`),
  KEY `iban` (`iban`),
  KEY `name` (`bank_name`),
  KEY `state` (`state`),
  KEY `user_id` (`user_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `user_payouts`
--

CREATE TABLE IF NOT EXISTS `user_payouts` (
  `payout_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `state` enum('new','payout','error','waiting') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `payout_account_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payout_account_id` int(10) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_id` int(10) unsigned NOT NULL,
  `add_date` datetime NOT NULL,
  `payout_date` datetime NOT NULL,
  `payout_by` int(10) unsigned NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`payout_id`),
  KEY `user_id` (`user_id`),
  KEY `state` (`state`),
  KEY `amount` (`amount`),
  KEY `transaction_id` (`transaction_id`),
  KEY `add_date` (`add_date`),
  KEY `payout_date` (`payout_date`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `user_registration_details`
--

CREATE TABLE IF NOT EXISTS `user_registration_details` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `registration_date` datetime NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(23) COLLATE utf8_unicode_ci NOT NULL,
  `user_agent` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  `whitelabel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lang` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `currency` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `locale` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `registration_type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `registration_date` (`registration_date`),
  KEY `registration_type` (`registration_type`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `role` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `customer_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `locale` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'en',
  `country` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'GB',
  `currency` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'EUR',
  `user_code` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `verified_email` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `last_login` datetime NOT NULL,
  `last_action` datetime NOT NULL,
  `last_ip` varchar(23) COLLATE utf8_unicode_ci NOT NULL,
  `budget` decimal(10,2) NOT NULL DEFAULT '0.00',
  `win` decimal(16,2) NOT NULL DEFAULT '0.00',
  `tmp_password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_2` (`username`),
  KEY `active` (`active`),
  KEY `role` (`role`),
  KEY `user_code` (`user_code`),
  KEY `locale` (`locale`),
  KEY `lang` (`lang`),
  KEY `country` (`country`),
  KEY `currency` (`currency`),
  KEY `customer_id` (`customer_id`),
  KEY `verified_email` (`verified_email`),
  KEY `bugdet` (`budget`),
  KEY `password` (`password`),
  KEY `tmp_password` (`tmp_password`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;
