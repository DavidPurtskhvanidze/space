CREATE TABLE IF NOT EXISTS `core_administrator` (
	`username` varchar(50) DEFAULT NULL,
	`password` varchar(50) DEFAULT NULL,
	`group` varchar(50) DEFAULT NULL,
	`verification_key` varchar(255) DEFAULT NULL,
	UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `core_administrator` (`username`, `password`, `group`) VALUES
	('admin', '*4ACFE3202A5FF5CF467898FC58AAB1D615029441', 'admin');

CREATE TABLE IF NOT EXISTS `core_cache` (
	`cache_type` varchar(50) NOT NULL DEFAULT 'default_type',
	`id` varchar(255) DEFAULT NULL,
	`expiration_date` datetime DEFAULT NULL,
	`data` longtext DEFAULT NULL,
	UNIQUE KEY `cache_type_id` (`cache_type`, `id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `custom_settings_settings` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id` varchar(255) DEFAULT NULL,
	`value` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `core_locations` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) DEFAULT NULL,
	`longitude` float DEFAULT NULL,
	`latitude` float DEFAULT NULL,
	PRIMARY KEY (`sid`),
	UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `core_uploaded_files` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`file_name` varchar(255) DEFAULT NULL,
	`file_group` varchar(255) DEFAULT NULL,
	`saved_file_name` varchar(255) DEFAULT NULL,
	`storage_method` varchar(255) DEFAULT 'file_system',
	`file_content` blob DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `core_settings` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) DEFAULT NULL,
	`value` text DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `core_settings` (`name`, `value`) VALUES
	('listing_picture_width', '350'),
	('listing_picture_height', '262'),
	('listing_thumbnail_width', '100'),
	('listing_thumbnail_height', '100'),
	('listing_big_picture_width', '800'),
	('listing_big_picture_height', '600'),
	('listing_picture_storage_method', 'file_system'),
	('notification_email', 'blackhole@worksforweb.com'),
	('task_scheduler_last_start_date', '2007-09-30 15:37:07'),
	('task_scheduler_last_end_date', '2007-09-30 15:37:27'),
	('system_email', 'blackhole@worksforweb.com'),
	('escape_html_tags', 'htmlpurifier'),
	('i18n_default_language', 'en'),
	('i18n_default_domain', 'PhrasesInTemplates'),
	('i18n_display_mode_for_not_translated_phrases', 'default'),
	('i18n_sort_translated_list_and_tree_values', '0'),
	('radius_search_unit', 'miles'),
	('google_maps_API_key', ''),
	('captcha_in_contact_form', '1'),
	('captcha_in_contact_seller_form', '1'),
	('captcha_in_registration_form', '1'),
	('captcha_in_tell_friend_form', '1'),
	('watermark_picture', 'watermark.gif'),
	('watermark_transparency', '40'),
  ('watermark_position', 'bottom-right'),
  ('captcha_in_contact_user_form', '1'),
	('enable_wysiwyg_editor', '1'),
	('user_balance_threshold', '10'),
	('DatabaseVersion', '3'),
	('jpeg_image_quality', '7'),
	('captcha_in_report_improper_content_form', '1'),
	('listings_to_update_keywords', ''),
	('max_time_to_execute_update_listings_keywords', '5'),
	('qr_code_ecc', 'L'),
	('qr_code_square_size', '4'),
	('qr_code_boundary_size', '4'),
	('product_version', '7.5.0'),
	('enable_share_block', '1'),
	('AdminPanel_currentTheme', 'default'),
	('FrontEnd_currentTheme', 'base'),
	('MobileFrontEnd_currentTheme', 'mobile_base'),
	('display_default_response_on_listing_not_found_and_deactivated', '1'),
	('redirect_uri_on_listing_not_found_and_deactivated', '/sorry/'),
	('lf_limit', '3'),
	('lf_time', '3'),
	('lf_time_block', '5'),
	('favicon_icon', ''),
	('cache_blocks_main_page', '0');

CREATE TABLE IF NOT EXISTS `core_partially_disabled_modules` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`module` varchar(255) NOT NULL DEFAULT 'main',
	`application_id` varchar(25) NOT NULL DEFAULT 'FrontEnd',
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` varchar(250) DEFAULT NULL,
  `subject` varchar(1000) DEFAULT NULL,
  `body` text DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `email_templates` (`id`, `subject`, `body`, `last_modified`) VALUES
('password_change_email', '{$GLOBALS.site_url}: Request to Change Admin Password', '<p>Dear {$adminInfo.username},<br />\r\n&nbsp;&nbsp; &nbsp;Someone, either you or someone else, submitted a request to change your admin password. If you did not place the request yourself, simply disregard this message. If you submitted the request, please go ahead and change your password by following the link below:<br />\r\n&nbsp;&nbsp; &nbsp;<a href="{$GLOBALS.site_url}/?action=change_password&amp;username={$adminInfo.username}&amp;verification_key={$adminInfo.verification_key}">Change your password</a></p>\r\n\r\n<p>Best regards,<br />\r\n{$GLOBALS.site_url}</p>\r\n', '2014-12-30 17:02:35'),
('password_guessing', 'Unsuccessful Admin Login Attempt', '<p>Dear Administrator,</p>\r\n\r\n<p><span style="color:rgb(38, 38, 38); font-family:arial,sans-serif">The system registered {$limit}&nbsp;unsuccessful admin login attempts with the username {$username}, coming from the following IP address: {$ip}.&nbsp;Timestamp - {$Timestamp}</span><br />\r\n<br />\r\n<span style="color:rgb(38, 38, 38); font-family:arial,sans-serif">The login attempts exceeded the max number of failed attempts, and the system suspended the account. It will be automatically unsuspended after the time specified in the System Settings.</span><br />\r\n<br />\r\n<span style="color:rgb(38, 38, 38); font-family:arial,sans-serif">If it weren''t you, please make sure your username/password combination meets prudent security requirements to make sure your system remains safe and secure.</span><br />\r\n<span style="color:rgb(38, 38, 38); font-family:arial,sans-serif">Automated response</span></p>\r\n', '2015-02-24 15:35:14');

CREATE TABLE IF NOT EXISTS `authentication_failures_blocklist` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  `email_sended` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `authentication_failures` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) DEFAULT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
