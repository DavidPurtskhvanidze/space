INSERT INTO `core_settings` (`name`, `value`) VALUES
	('youtube_category', '2');

CREATE TABLE IF NOT EXISTS `field_types_token` (
  `token` text COLLATE utf8_unicode_ci NOT NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `field_types_youtube_files_stack` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `uploaded_file_sid` int(11) NOT NULL,
  `listing_sid` int(11) NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
