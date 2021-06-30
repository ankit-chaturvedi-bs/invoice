INSERT IGNORE INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES  ('invoice', 'Invoice', 'Invoice Creator ', '4.0.0', 1, 'extra') ;


DROP TABLE IF EXISTS `engine4_invoice_invoices`;
CREATE TABLE `engine4_invoice_invoices` (
  `invoice_id` int UNSIGNED NOT NULL auto_increment,
  `creator_id` varchar(128) NOT NULL,
  `creator_name` longtext NOT NULL,
  `customer_name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `invoice_number` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `address` varchar(100) NOT NULL,
  `contact_number` varchar(60) NOT NULL,
  `customer_email` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `discount` int UNSIGNED NOT NULL DEFAULT '0',
  `currency` int UNSIGNED NOT NULL DEFAULT '0',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `cgst` int UNSIGNED NOT NULL DEFAULT '0',
  `igst` int UNSIGNED NOT NULL DEFAULT '0',
  `sgst` int UNSIGNED NOT NULL DEFAULT '0',
  `category_id` int NOT NULL,
  `type` int NOT NULL DEFAULT '0',
  `total` int NOT NULL,
  PRIMARY KEY (`invoice_id`),
  KEY (`invoice_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;



DROP TABLE IF EXISTS `engine4_invoice_products`;
CREATE TABLE `engine4_invoice_products` (
  `product_id` int(11) unsigned NOT NULL auto_increment,
  `invoice_number` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `product_name`  varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `quantity` int(11) unsigned NOT NULL,
  `price` int(11) unsigned NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci ;


/*
	* admin nav menu items
*/
INSERT IGNORE INTO `engine4_core_menuitems` (`name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `order`) VALUES
('core_main_invoice', 'invoice', 'Invoices', '', '{"route":"invoice_general","icon":"fa fa-pencil-alt"}', 'core_main', '', 4),


('invoice_main_manage', 'invoice', 'My Entries', '', '{"route":"invoice_general","action":"manage","icon":"fa fa-user"}', 'invoice_main', '', 1),
('invoice_main_create', 'invoice', 'Write New Entry', '', '{"route":"invoice_general","action":"create","icon":"fa fa-pencil-alt"}', 'invoice_main', '', 2),

('invoice_admin_main_manage', 'invoice', 'View invoices', '', '{"route":"admin_default","module":"invoice","controller":"manage"}', 'invoice_admin_main', '', 1),
('invoice_admin_main_settings', 'invoice', 'Global Settings', '', '{"route":"admin_default","module":"invoice","controller":"settings"}', 'invoice_admin_main', '', 2),
('invoice_admin_main_level', 'invoice', 'Member Level Settings', '', '{"route":"admin_default","module":"invoice","controller":"level"}', 'invoice_admin_main', '', 3),
('invoice_admin_main_categories', 'invoice', 'Categories', '', '{"route":"admin_default","module":"invoice","controller":"settings", "action":"categories"}', 'invoice_admin_main', '', 4),
('core_admin_main_plugins_invoice', 'invoice', 'invoices', '', '{"route":"admin_default","module":"invoice","controller":"manage"}', 'core_admin_main_plugins', '', 999);



INSERT IGNORE INTO `engine4_core_menus` (`name`, `type`, `title`) VALUES
('invoice_main', 'standard', 'Invoice Main Navigation Menu');
-- --------------------------------------------------------
-- --------------------------------------------------------

--
-- Table structure for table `engine4_invoice_categories`
--

CREATE TABLE `engine4_invoice_categories` (
  `category_id` int NOT NULL,
  `category_name` text NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


INSERT IGNORE INTO `engine4_invoice_categories` (`category_id`, `category_name`) VALUES
(1,  'Social EngineAddOns'),
(2, 'Prime Messenger'),
(3, 'Alma Hub'),
(5, 'Mage Cube'),
(6, 'Other Projects');


--
-- Creator 
--

INSERT INTO `engine4_authorization_levels` ( `title`, `description`, `type`, `flag`) 
VALUES ('Creator', 'Invoice Creator', 'user', NULL);


--
-- Dumping data for table `engine4_authorization_permissions`
--

INSERT INTO `engine4_authorization_permissions` (`level_id`, `type`, `name`, `value`, `params`) VALUES
(1, 'invoice', 'create', 1, NULL),
(1, 'invoice', 'delete', 2, NULL),
(1, 'invoice', 'edit', 2, NULL),
(1, 'invoice', 'flood', 5, '[\"0\",\"minute\"]'),
(1, 'invoice', 'max', 3, '50'),
(1, 'invoice', 'view', 2, NULL),
(2, 'invoice', 'create', 1, NULL),
(2, 'invoice', 'delete', 2, NULL),
(2, 'invoice', 'edit', 2, NULL),
(2, 'invoice', 'flood', 5, '[\"0\",\"minute\"]'),
(2, 'invoice', 'max', 3, '50'),
(2, 'invoice', 'view', 2, NULL),
(3, 'invoice', 'create', 0, NULL),
(3, 'invoice', 'delete', 0, NULL),
(3, 'invoice', 'edit', 0, NULL),
(3, 'invoice', 'flood', 5, '[\"0\",\"minute\"]'),
(3, 'invoice', 'max', 3, '50'),
(3, 'invoice', 'view', 0, NULL),
(4, 'invoice', 'create', 0, NULL),
(4, 'invoice', 'delete', 0, NULL),
(4, 'invoice', 'edit', 0, NULL),
(4, 'invoice', 'flood', 5, '[\"0\",\"minute\"]'),
(4, 'invoice', 'max', 3, '50'),
(4, 'invoice', 'view', 0, NULL),
(5, 'invoice', 'view', 0, NULL),
(6, 'invoice', 'create', 1, NULL),
(6, 'invoice', 'delete', 1, NULL),
(6, 'invoice', 'edit', 1, NULL),
(6, 'invoice', 'flood', 5, '[\"0\",\"minute\"]'),
(6, 'invoice', 'max', 3, '50'),
(6, 'invoice', 'view', 1, NULL);


--
-- Dumping data for table `engine4_core_settings`
--

INSERT INTO `engine4_core_settings` (`name`, `value`) VALUES
('invoice.account.address', 'xxxxxx'),
('invoice.account.name', 'xxxxxx'),
('invoice.account.no', 'xxxxxx'),
('invoice.bank.name', 'xxxxxx'),
('invoice.cgst', '8'),
('invoice.currency', '0'),
('invoice.gst.no', 'xxxxxx'),
('invoice.ifsc.code', 'xxxxxx'),
('invoice.igst', '18'),
('invoice.lut.no', 'xxxxxx'),
('invoice.page', '10'),
('invoice.pan.no', '121321231'),
('invoice.sgst', '18');


