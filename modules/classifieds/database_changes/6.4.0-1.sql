ALTER TABLE  `classifieds_categories`
ADD  `meta_keywords` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
ADD  `meta_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
ADD  `page_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE  `classifieds_listings`
ADD  `meta_keywords` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
ADD  `meta_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
ADD  `page_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `classifieds_listing_fields_order` (
  `category_sid` int(10) unsigned NOT NULL DEFAULT '0',
  `field_sid` int(10) unsigned NOT NULL DEFAULT '0',
  `order` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`category_sid`,`field_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
