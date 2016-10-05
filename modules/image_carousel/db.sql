INSERT INTO `core_settings` (`name`, `value`) VALUES
	('image_carousel_width', '640'),
	('image_carousel_height', '480'),
	('image_carousel_transition_time', '3'),
	('image_carousel_show_arrows', '1'),
	('image_carousel_show_numbers', '0');

CREATE TABLE IF NOT EXISTS `image_carousel_images` (
	`sid` int(10) unsigned NOT NULL auto_increment,
	`caption` varchar(255) DEFAULT NULL,
	`url` text DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `image_file_name` varchar(200) DEFAULT NULL,
  `image_file_size` int(11) DEFAULT NULL,
  `image_content_type` varchar(30) DEFAULT NULL,
	`display_order` int(10) unsigned default NULL,
	`disabled` tinyint(1) default '0',
	PRIMARY KEY  (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
