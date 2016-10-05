CREATE TABLE IF NOT EXISTS `core_partially_disabled_modules` (
  `sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `application_id` varchar(25) NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
