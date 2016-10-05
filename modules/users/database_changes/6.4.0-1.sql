ALTER TABLE `users_profile_fields`
	ADD `levels_ids` varchar(250) DEFAULT NULL,
 	ADD `levels_captions` varchar(250) DEFAULT NULL;

CREATE TABLE `users_profile_field_tree` (
 `sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `field_sid` int(10) unsigned DEFAULT NULL,
 `parent_sid` int(10) unsigned DEFAULT NULL,
 `level` int(10) unsigned DEFAULT NULL,
 `order` int(10) unsigned DEFAULT NULL,
 `caption` varchar(255) DEFAULT NULL,
 PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO  `site_pages_pages` (
	`uri` ,
	`pass_parameters_via_uri` ,
	`module` ,
	`function` ,
	`template` ,
	`title` ,
	`application_id` ,
	`parameters` ,
	`keywords` ,
	`description` ,
	`serialized_extra_info`
)
VALUES 
('/edit_user_profile_field/edit_tree/', NULL ,  'users',  'edit_tree',  '',  'Edit Tree',  'AdminPanel',  '',  '',  '', NULL),
('/edit_user_profile_field/import_tree_data/', NULL ,  'users',  'import_tree_data',  '',  'Import Tree Data',  'AdminPanel',  '',  '',  '', NULL);
