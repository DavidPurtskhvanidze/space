CREATE TABLE IF NOT EXISTS `google_map_geocodes` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`address` text DEFAULT NULL,
	`latitude` float DEFAULT NULL,
	`longitude` float DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
