INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
	('payment_callback', '/callback/', NULL, 'payment', 'callback', '', '', 'FrontEnd', 'N;', '', ''),
	('user_payments', '/user/payments/', 0, 'payment', 'payments', 'user_payments.tpl', 'Payments', 'FrontEnd', 'N;', '', '');

CREATE TABLE IF NOT EXISTS `payment_payments` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`invoice_sid` int(10) unsigned DEFAULT NULL,
	`user_sid` int(10) unsigned DEFAULT NULL,
	`deleted_user_username` varchar(255) DEFAULT NULL,
	`description` text DEFAULT NULL,
	`product_info` text DEFAULT NULL,
	`amount` decimal(10,2) DEFAULT NULL,
	`creation_date` datetime DEFAULT NULL,
	`status` varchar(255) DEFAULT NULL,
	`callback_data` text DEFAULT NULL,
	`product_id` varchar(120) DEFAULT NULL,
	`last_updated` datetime DEFAULT NULL,
	`payment_gateway_id` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=131483647;

CREATE TABLE IF NOT EXISTS `payment_gateways` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id` varchar(64)  DEFAULT NULL,
	`serialized_extra_info` text  DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
