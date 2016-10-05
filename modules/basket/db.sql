CREATE TABLE IF NOT EXISTS `basket_baskets` (
  `sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_sid` int(10) unsigned DEFAULT NULL,
  `listing_sid` int(10) unsigned DEFAULT NULL ,
  `option_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `basket_container_items` (
  `sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `listing_sid` int(10) unsigned DEFAULT NULL,
  `option_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM;

INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`)
VALUES('basket', '/basket', 1, 'basket', 'my_basket', 'basket_page.tpl', 'My basket', 'FrontEnd', 'a:0:{}', '', '');
