CREATE TABLE IF NOT EXISTS `payment_system_invoices` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`user_sid` int(10) unsigned DEFAULT NULL,
	`amount` decimal(10,2) DEFAULT NULL,
	`product_id` varchar(255) DEFAULT NULL,
	`product_description` text DEFAULT NULL,
	`product_info` text DEFAULT NULL,
	`product_info_template` varchar(255) DEFAULT NULL,
	`payment_method_class_name` varchar(255) DEFAULT NULL,
	`transaction_sid` int(10) unsigned DEFAULT NULL,
	`status` varchar(50) DEFAULT NULL,
	`creation_date` datetime DEFAULT NULL,
	`last_updated` datetime DEFAULT NULL,
	`payment_queued_action` text DEFAULT NULL,
	`success_action` longtext DEFAULT NULL,
	`success_page_url` varchar(255) DEFAULT NULL,
	`failure_action` text DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=342483647;

INSERT INTO `core_settings` (`name`, `value`) VALUES
	('payment_method', 'modules\\payment\\lib\\PaymentMethodMoney');
