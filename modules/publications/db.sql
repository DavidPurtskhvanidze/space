CREATE TABLE IF NOT EXISTS `publications_articles` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(255) DEFAULT NULL,
	`description` mediumtext DEFAULT NULL,
	`text` longtext DEFAULT NULL,
	`category_sid` int(10) unsigned NOT NULL DEFAULT 0,
	`date` datetime  DEFAULT NULL,
  `picture_url` varchar(255) DEFAULT NULL,
  `picture` varchar(200) DEFAULT NULL,
  `picture_file_name` varchar(200) DEFAULT NULL,
  `picture_file_size` int(11) DEFAULT NULL,
  `picture_content_type` varchar(30) DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `publications_categories` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id` varchar(255)  DEFAULT NULL,
	`title` varchar(255) DEFAULT NULL,
	`order` int(11) DEFAULT '0',
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
	('publications', '/publications/', NULL, 'publications', 'list_categories', '', '', 'AdminPanel', '', '', ''),
	('news', '/news/', NULL, 'publications', 'show_publications', '', 'News', 'FrontEnd', 'a:1:{s:11:\"category_id\";s:4:\"News\";}', '', ''),
	('publications', '/publications/', 1, 'publications', 'show_publications', '', '', 'FrontEnd', 'a:0:{}', '', '');

INSERT INTO `core_settings` (`name`, `value`) VALUES
('article_picture_width', '150'),
('article_picture_height', '150'),
('article_picture_storage_method', 'file_system');
