CREATE TABLE IF NOT EXISTS `ip_blocklist_blocklist` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`start_ip` int(10) unsigned DEFAULT NULL,
	`end_ip` int(10) unsigned DEFAULT NULL,
	`cidr_mask` tinyint(4) DEFAULT NULL,
	`comment` varchar(255) DEFAULT NULL,
	`added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
