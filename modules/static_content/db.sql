CREATE TABLE IF NOT EXISTS `static_content_pages` (
	`id` varchar(255)  DEFAULT NULL,
	`name` varchar(255) DEFAULT NULL,
	`content` text  DEFAULT NULL,
	`title` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
	('stat_pages', '/stat_pages/', NULL, 'static_content', 'edit_static_content', '', '', 'AdminPanel', '', '', ''),
	('about', '/about/', NULL, 'static_content', 'show_static_content', '', 'About us', 'FrontEnd', 'a:1:{s:6:\"pageid\";s:5:\"About\";}', '', ''),
	('about', '/about/', NULL, 'static_content', 'show_static_content', '', 'About us', 'MobileFrontEnd', 'a:1:{s:6:\"pageid\";s:5:\"About\";}', '', '');
