INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
	('edit_page_templates', '/edit_page_templates/', NULL, 'template_manager', 'edit_page_templates', '', '', 'AdminPanel', '', '', ''),
	('edit_templates', '/edit_templates/', NULL, 'template_manager', 'edit_templates', '', '', 'AdminPanel', '', '', ''),
	('edit_themes', '/edit_themes/', NULL, 'template_manager', 'theme_editor', '', '', 'AdminPanel', '', '', ''),
	('edit_mobile_templates', '/edit_mobile_templates/', 1, 'template_manager', 'edit_templates', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:14:\"MobileFrontEnd\";}', '', ''),
	('edit_mobile_page_templates', '/edit_mobile_page_templates/', 1, 'template_manager', 'edit_page_templates', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:14:\"MobileFrontEnd\";}', '', ''),
	('edit_mobile_themes', '/edit_mobile_themes/', 1, 'template_manager', 'theme_editor', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:14:\"MobileFrontEnd\";}', '', ''),
  ('edit_subdomain_templates', '/edit_subdomain_templates/', 1, 'template_manager', 'edit_templates', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:9:\"SubDomain\";}', '', ''),
  ('edit_subdomain_page_templates', '/edit_subdomain_page_templates/', 1, 'template_manager', 'edit_page_templates', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:9:\"SubDomain\";}', '', ''),
	('edit_subdomain_themes', '/edit_subdomain_themes/', 1, 'template_manager', 'theme_editor', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:9:\"SubDomain\";}', '', '');

CREATE TABLE IF NOT EXISTS `template_manager_colorize` (
  `theme` varchar(250) DEFAULT NULL,
  `styles` text DEFAULT NULL,
  `lastModified` datetime DEFAULT NULL,
  PRIMARY KEY (`theme`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
