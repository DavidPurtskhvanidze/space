ALTER TABLE `users_users` ADD `third_party_id` varchar(255) DEFAULT NULL;

INSERT INTO `core_settings` (`name`, `value`) VALUES
	('third_party_auth_user_group_sid', NULL);

INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
	('user_openid_oauth_login', '/user/openid_oauth_login/', 0, 'third_party_login', 'openid_oauth_login', '', 'OpenID or OAuth Login', 'FrontEnd', 'a:0:{}', '', ''),
	('user_openid_oauth_login', '/user/openid_oauth_login/', 0, 'third_party_login', 'openid_oauth_login', '', 'OpenID or OAuth Login', 'MobileFrontEnd', 'a:0:{}', '', '');
