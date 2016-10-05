CREATE TABLE IF NOT EXISTS `business_catalog_categories` (
	`id` varchar(255) DEFAULT NULL,
	`name` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `business_catalog_records` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`category_id` varchar(255) NOT NULL DEFAULT 0,
	`name` varchar(255) DEFAULT NULL,
	`description` text,
	`address` varchar(255) DEFAULT NULL,
	`phone` varchar(255) DEFAULT NULL,
	`fax` varchar(255) DEFAULT NULL,
	`location` varchar(255) DEFAULT NULL,
	`email` varchar(255) DEFAULT NULL,
	`url` varchar(255) DEFAULT NULL,
	`full` text,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
('business', '/business/', NULL, 'business_catalog', 'edit_business_catalog', '', '', 'AdminPanel', '', '', ''),
('service_providers', '/service-providers/', 0, 'business_catalog', 'show_business_catalog', '', 'Service Providers', 'FrontEnd', 'a:1:{s:11:\"category_id\";s:13:\"WebDevelopers\";}', '', '');
