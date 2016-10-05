CREATE TABLE IF NOT EXISTS `listing_repost_social_network_service_data` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`user_sid` int(10) unsigned NOT NULL DEFAULT '0',
	`provider_id` varchar(255) DEFAULT NULL,
	`access_token` varchar(2048) DEFAULT NULL,
	`enabled` tinyint(1) DEFAULT '0',
	PRIMARY KEY (`sid`),
	UNIQUE KEY `service` (`user_sid`,`provider_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `classifieds_listings` ADD `facebook_repost_status` tinyint(1) NOT NULL DEFAULT '0';
ALTER TABLE `classifieds_listings` ADD `twitter_repost_status` tinyint(1) NOT NULL DEFAULT '0';
