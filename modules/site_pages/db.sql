CREATE TABLE IF NOT EXISTS `site_pages_pages` (
	`id` varchar(255) NOT NULL,
	`uri` varchar(255) DEFAULT NULL,
	`no_index` tinyint(4) NOT NULL DEFAULT 0,
	`pass_parameters_via_uri` int(1) DEFAULT NULL,
	`module` varchar(255) DEFAULT NULL,
	`function` varchar(255) DEFAULT NULL,
	`template` varchar(255) DEFAULT NULL,
	`title` varchar(255) DEFAULT NULL,
	`application_id` varchar(25) NOT NULL DEFAULT 'FrontEnd',
	`parameters` text DEFAULT NULL,
	`keywords` text DEFAULT NULL,
	`description` text DEFAULT NULL,
	`serialized_extra_info` text DEFAULT NULL,
	UNIQUE KEY `UNIQUE_KEY` (`uri`,`application_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
	('site_pages', '/site_pages/', NULL, 'site_pages', 'list_site_pages', '', '', 'AdminPanel', '', '', ''),
	('mobile_site_pages', '/mobile_site_pages/', 1, 'site_pages', 'list_site_pages', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:14:\"MobileFrontEnd\";}', '', ''),
	('manage_mobile_forms', '/mobile_manage_forms/', 1, 'form_manager', 'manage_forms', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:14:\"MobileFrontEnd\";}', '', ''),
	('subdomain_site_pages', '/subdomain_site_pages/', 1, 'site_pages', 'list_site_pages', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:9:\"SubDomain\";}', '', '');

INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
	('root', '/', NULL, 'module_manager', 'manage_modules', '', '', 'AdminPanel', '', '', ''),
	('root', '/', NULL, 'main', 'display_template', 'home_page.tpl', 'Classifieds Script Demo', 'FrontEnd', 'a:1:{s:13:\"template_file\";s:25:\"homepage_main_content.tpl\";}', '', ''),
	('root', '/', NULL, 'main', 'display_template', '', 'Classifieds Script Demo', 'MobileFrontEnd', 'a:1:{s:13:\"template_file\";s:12:\"homepage.tpl\";}', '', '');
