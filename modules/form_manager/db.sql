CREATE TABLE IF NOT EXISTS `form_manager_forms` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id` varchar(255) DEFAULT NULL,
	`title` varchar(255) DEFAULT NULL,
	`type` varchar(250) DEFAULT NULL,
	`category_sid` int(10) DEFAULT NULL,
	`application_id` varchar(100) NOT NULL DEFAULT 'FrontEnd',
	PRIMARY KEY (`sid`),
	KEY `application_id` (`application_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `form_manager_fields` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`form_sid` int(10) DEFAULT NULL,
	`field_id` varchar(255) DEFAULT NULL,
	`caption` VARCHAR(255) DEFAULT NULL,
	`order` int(10) DEFAULT 0,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
