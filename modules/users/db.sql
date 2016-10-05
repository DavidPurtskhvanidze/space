INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
	('users', '/users/', NULL, 'users', 'users', '', 'Users', 'AdminPanel', '', '', ''),
	('edit_user', '/edit_user/', NULL, 'users', 'edit_user', '', 'Edit User', 'AdminPanel', '', '', ''),
	('user_groups', '/user_groups/', NULL, 'users', 'user_groups', '', 'User Groups', 'AdminPanel', '', '', ''),
	('add_user_group', '/add_user_group/', NULL, 'users', 'add_user_group', '', 'Add a New User Group', 'AdminPanel', '', '', ''),
	('edit_user_group', '/edit_user_group/', NULL, 'users', 'edit_user_group', '', 'Edit User Group', 'AdminPanel', '', '', ''),
	('delete_user_group', '/delete_user_group/', NULL, 'users', 'delete_user_group', '', 'Delete User Group', 'AdminPanel', '', '', ''),
	('user_profile_fields', '/user_profile_fields/', NULL, 'users', 'edit_user_profile', '', '', 'AdminPanel', '', '', ''),
	('add_user_profile_field', '/add_user_profile_field/', NULL, 'users', 'add_user_profile_field', '', '', 'AdminPanel', '', '', ''),
	('edit_user_profile', '/edit_user_profile/', NULL, 'users', 'edit_user_profile', '', '', 'AdminPanel', '', '', ''),
	('delete_user_profile_field', '/delete_user_profile_field/', NULL, 'users', 'delete_user_profile_field', '', '', 'AdminPanel', '', '', ''),
	('edit_user_profile_field_edit_list', '/edit_user_profile_field/edit_list/', NULL, 'users', 'edit_list', '', 'Edit List', 'AdminPanel', '', '', ''),
	('edit_user_profile_field_edit_list_item', '/edit_user_profile_field/edit_list_item/', NULL, 'users', 'edit_list_item', '', 'Edit List Item', 'AdminPanel', '', '', ''),
	('edit_user_profile_field', '/edit_user_profile_field/', NULL, 'users', 'edit_user_profile_field', '', 'Edit User Profile Field', 'AdminPanel', '', '', ''),
  ('edit_user_profile_field_edit_tree', '/edit_user_profile_field/edit_tree/', NULL ,  'users',  'edit_tree',  '',  'Edit Tree',  'AdminPanel',  '',  '',  ''),
  ('edit_user_profile_field_import_tree_data', '/edit_user_profile_field/import_tree_data/', NULL ,  'users',  'import_tree_data',  '',  'Import Tree Data',  'AdminPanel',  '',  '',  ''),
  ('users', '/users/', 1, 'users', 'user_details', 'user_details.tpl', 'User Profile', 'FrontEnd', 'N;', '', ''),
  ('user_login', '/user/login/', NULL, 'users', 'login', '', 'User login form', 'FrontEnd', '', '', ''),
  ('user_registration', '/user/registration/', NULL, 'users', 'registration', '', 'Registration', 'FrontEnd', 'N;', '', ''),
  ('user_logout', '/user/logout/', 0, 'users', 'logout', '', '', 'FrontEnd', 'N;', '', ''),
  ('password_recovery', '/password-recovery/', 0, 'users', 'password_recovery', '', 'Password Recovery', 'FrontEnd', 'N;', '', ''),
  ('user_profile', '/user/profile/', 0, 'users', 'edit_profile', 'user_profile.tpl', 'Edit Profile', 'FrontEnd', 'N;', '', ''),
  ('users_search', '/users/search/', 0, 'users', 'search_users', 'users_search.tpl', 'Search Sellers', 'FrontEnd', 'a:2:{s:13:\"user_group_id\";s:6:\"Dealer\";s:6:\"fields\";s:27:\"DealershipName, City, State\";}', '', ''),
  ('users_listings', '/users/listings/', 1, 'users', 'user_details', 'search.tpl', 'User Ads', 'FrontEnd', 'a:1:{s:16:\"display_template\";s:29:\"user_details_with_all_ads.tpl\";}', '', ''),
  ('users_contact', '/users/contact/', 1, 'users', 'contact_form', '', 'Contact User', 'FrontEnd', 'N;', '', ''),
  ('user_notifications', '/user/notifications/', 0, 'users', 'user_notifications', '', 'Notifications', 'FrontEnd', 'N;', '', ''),
  ('users', '/users/', 1, 'users', 'user_details', '', 'User Profile', 'MobileFrontEnd', 'a:0:{}', '', ''),
  ('users_search', '/users/search/', 0, 'users', 'search_users', '', 'Search Sellers', 'MobileFrontEnd', 'a:2:{s:13:\"user_group_id\";s:6:\"Dealer\";s:6:\"fields\";s:27:\"DealershipName, City, State\";}', '', ''),
  ('users_contact', '/users/contact/', 1, 'users', 'contact_form', '', 'Contact User', 'MobileFrontEnd', 'a:0:{}', '', ''),
  ('users_listings', '/users/listings/', 1, 'users', 'user_details', '', 'User Ads', 'MobileFrontEnd', 'a:1:{s:16:\"display_template\";s:29:\"user_details_with_all_ads.tpl\";}', '', ''),
  ('user_login', '/user/login/', 0, 'users', 'login', '', 'User login form', 'MobileFrontEnd', 'a:0:{}', '', ''),
	('user_logout', '/user/logout/', 0, 'users', 'logout', '', '', 'MobileFrontEnd', 'a:0:{}', '', '');

UPDATE `site_pages_pages` SET `no_index` = 1 WHERE `uri` = '/users/search/';
UPDATE `site_pages_pages` SET `no_index` = 1 WHERE `uri` = '/users/listings/';

CREATE TABLE IF NOT EXISTS `users_relations_user_groups_membership_plans` (
	`user_group_sid` int(10) unsigned DEFAULT NULL,
	`membership_plan_sid` int(10) unsigned DEFAULT NULL,
	UNIQUE KEY `user_group_id` (`user_group_sid`,`membership_plan_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users_sessions` (
	`session_key` varchar(32) DEFAULT NULL,
	`user_sid` int(11) NOT NULL DEFAULT '0',
	`remote_ip` varchar(32) DEFAULT NULL,
	`user_agent` varchar(255) DEFAULT NULL,
	`start` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`session_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users_profile_field_list` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`field_sid` int(10) unsigned DEFAULT NULL,
	`order` int(10) unsigned DEFAULT NULL,
	`rank` int(10) unsigned DEFAULT NULL,
	`value` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `users_profile_field_tree` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`field_sid` int(10) unsigned DEFAULT NULL,
	`parent_sid` int(10) unsigned DEFAULT NULL,
	`level` int(10) unsigned DEFAULT NULL,
	`order` int(10) unsigned DEFAULT NULL,
	`caption` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users_profile_fields` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`user_group_sid` int(10) unsigned DEFAULT NULL,
	`order` int(10) unsigned DEFAULT NULL,
	`id` varchar(250) DEFAULT NULL,
	`caption` varchar(250) DEFAULT NULL,
	`type` varchar(250) DEFAULT NULL,
	`is_required` varchar(250) DEFAULT NULL,
	`width` varchar(250) DEFAULT NULL,
	`height` varchar(250) DEFAULT NULL,
	`storage_method` varchar(250) DEFAULT NULL,
	`maxlength` varchar(250) DEFAULT NULL,
	`levels_ids` varchar(250) DEFAULT NULL,
 	`levels_captions` varchar(250) DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users_user_groups` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id` varchar(255) DEFAULT NULL,
	`name` varchar(100) DEFAULT NULL,
	`active` tinyint(1) DEFAULT 1,
	`reg_form_template` varchar(60) DEFAULT NULL,
	`description` text DEFAULT NULL,
	`immediate_activation` tinyint(1) DEFAULT NULL,
	`user_menu_template` varchar(60) DEFAULT NULL,
	`make_user_trusted` tinyint(1) DEFAULT NULL,
	PRIMARY KEY (`sid`),
	UNIQUE KEY `ufi` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users_notifications` (
	`user_sid` int(10) unsigned NOT NULL DEFAULT 0,
	`notification_id` varchar(255) DEFAULT NULL,
	`value` int(10) unsigned DEFAULT NULL,
	UNIQUE KEY `user_sid_notification_id` (`user_sid`, `notification_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users_users` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`username` varchar(255) DEFAULT NULL,
	`password` varchar(255) DEFAULT NULL,
	`email` varchar(255) DEFAULT NULL,
	`user_group_sid` int(10) unsigned DEFAULT NULL,
	`registration_date` datetime DEFAULT NULL,
	`active` int(1) unsigned DEFAULT '0',
	`contract_sid` int(10) unsigned DEFAULT NULL,
	`activation_key` varchar(255) DEFAULT NULL,
	`verification_key` varchar(255) DEFAULT NULL,
	`trusted_user` int(1) unsigned DEFAULT '0',
	PRIMARY KEY (`sid`),
	UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `core_settings` (`name`, `value`) VALUES
	('notify_on_user_registration', '1'),
	('notify_on_user_contract_expiration', '1'),
	('notify_user_balance_is_lower', '1');

INSERT INTO `email_templates` (`id`, `subject`, `body`, `last_modified`) VALUES
('contact_form_message', 'Comments From {$FullName} On {$GLOBALS.site_url}', 'Full Name: {$FullName}<br />\r\n	Email: {$Email}<br />\r\n	Request: {$Request}<br /><br />\r\n	\r\n	Best regards,<br />\r\n	Administrator,<br />\r\n	{$GLOBALS.site_url}', '2015-01-14 17:18:21'),
('admin_user_registration_email', 'New User {$user.username} Has Just Registered On {$GLOBALS.site_url}', 'Dear Admin,<br /><br />\r\n	\r\n	We have a new user who signed up on your website. Here are the details:<br /><br />\r\n	\r\n	<p>User ID: {$user.sid}</p>\r\n	<p>Username: {$user.username}</p>\r\n	<p>User Email: {$user.email}</p><br />\r\n	\r\n	You can view his/her user profile <a href="{$GLOBALS.site_url}/admin/edit_user/?username={$user.username}">here</a> <br /><br />\r\n	{$GLOBALS.site_url} Administrator', '2015-01-14 17:17:32'),
('activate_account', 'New Account Activation On {page_url id=''root'' app=''FrontEnd''}', '<p>Hello!</p>\r\n\r\n<p><span style="line-height:1.6em">You have created a new account, "{$user.username}", on </span><a href="{page_url id=''root'' app=''FrontEnd''}" style="line-height: 1.6em;">{page_url id=''root'' app=''FrontEnd''}</a><span style="line-height:1.6em">.</span></p>\r\n\r\n<p>To activate your account, please click the link below:<span style="color:#e8bf6a"> </span><a href="{page_url module=''users'' function=''activate_account'' app=''FrontEnd''}?username={$user.username}&amp;activation_key={$user.activation_key}">Activate your account</a></p>\r\n\r\n<p>Thank you and welcome aboard, <span style="color:#e8bf6a"> </span></p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<div><a href="{page_url id=''root'' app=''FrontEnd''}">{page_url id=''root'' app=''FrontEnd''}</a> <span style="color:rgb(232, 191, 106); font-family:sans-serif,arial,verdana,trebuchet ms"> </span><span style="font-family:sans-serif,arial,verdana,trebuchet ms">Administrator</span></div>\r\n', '2015-11-30 12:44:29'),
('user_change_password', '{page_url id=''root'' app=''FrontEnd''}: Request to Change User Password', '<p>Dear {$user.username},<br />\r\n<br />\r\nSomeone, either you or someone else, submitted a request to change your user password. If you did not place the request yourself, simply disregard this message. If you submitted the request, please go ahead and change your password by following the link below:<br />\r\n<a href="{page_url module=''users'' function=''change_password'' app=''FrontEnd''}?username={$user.username}&amp;verification_key={$user.verification_key}">Change your password</a><br />\r\n<br />\r\nBest regards,<br />\r\nAdministrator, {page_url id=''root'' app=''FrontEnd''}</p>\r\n', '2015-05-22 15:04:19'),
('user_group_change_email', 'Your User Group has been changed on {$userSiteUrl}', '<p>Dear {$user.username},<br />\r\nYour current User Group has been changed to {$userGroupInfo.name}. Please fill in new account fields on the Profile page.<br />\r\nThank you!</p>\r\n\r\n<p>{$userSiteUrl} Administrator.</p>\r\n', '2015-01-21 12:15:47');
