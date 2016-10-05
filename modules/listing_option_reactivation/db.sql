CREATE TABLE IF NOT EXISTS `listing_option_reactivation_reactivations` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`user_sid` int(10) unsigned DEFAULT NULL,
	`listing_sid` int(10) unsigned DEFAULT NULL,
	`package_sid` int(10) unsigned DEFAULT NULL,
	`package_info` text DEFAULT NULL,
	`options_to_activate` text DEFAULT NULL,
	`activated` tinyint(1) NOT NULL DEFAULT 0,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
