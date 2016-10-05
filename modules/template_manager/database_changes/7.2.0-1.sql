INSERT INTO `site_pages_pages` (`uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
  ('/edit_subdomain_templates/', 1, 'template_manager', 'edit_templates', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:9:\"SubDomain\";}', '', ''),
  ('/edit_subdomain_page_templates/', 1, 'template_manager', 'edit_page_templates', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:9:\"SubDomain\";}', '', ''),
  ('/edit_subdomain_themes/', 1, 'template_manager', 'theme_editor', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:9:\"SubDomain\";}', '', '');

CREATE TABLE IF NOT EXISTS `template_manager_colorize` (
  `theme` varchar(250) NOT NULL,
  `styles` text NOT NULL,
  `lastModified` datetime NOT NULL,
  PRIMARY KEY (`theme`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
