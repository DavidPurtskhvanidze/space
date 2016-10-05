INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
	('membership_plan_edit', '/membership_plan/edit/', NULL, 'membership_plan', 'edit_membership_plan', '', '', 'AdminPanel', '', '', ''),
	('membership_plan_add', '/membership_plan/add/', NULL, 'membership_plan', 'add_membership_plan', '', '', 'AdminPanel', '', '', ''),
	('membership_plans', '/membership_plans/', NULL, 'membership_plan', 'membership_plans', '', '', 'AdminPanel', '', '', ''),
	('membership_plan_package_edit', '/membership_plan/package/edit/', NULL, 'membership_plan', 'edit_package', '', '', 'AdminPanel', '', '', ''),
	('membership_plan_package_add', '/membership_plan/package/add/', NULL, 'membership_plan', 'add_package', '', '', 'AdminPanel', '', '', ''),
	('membership_plan_packages', '/membership_plan/packages/', NULL, 'membership_plan', 'packages', '', '', 'AdminPanel', '', '', ''),
  ('user_subscription', '/user/subscription/', 0, 'membership_plan', 'subscription_page', 'user_subscription.tpl', 'Subscription Page', 'FrontEnd', 'N;', '', '');

CREATE TABLE IF NOT EXISTS `membership_plan_contracts` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`membership_plan_sid` int(10) unsigned DEFAULT NULL,
	`type` varchar(255) DEFAULT NULL,
	`creation_date` date DEFAULT NULL,
	`expired_date` date DEFAULT NULL,
	`price` decimal(30,2) unsigned DEFAULT NULL,
	`auto_extend` int(1) unsigned DEFAULT NULL,
	`serialized_extra_info` text DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `membership_plan_contract_packages` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`class_name` varchar(255) DEFAULT NULL,
	`contract_sid` int(10) unsigned DEFAULT NULL,
	`package_sid` int(10) unsigned DEFAULT NULL,
	`fields` text DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `membership_plan_plans` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) DEFAULT NULL,
	`description` text DEFAULT NULL,
	`price` decimal(30,2) DEFAULT NULL,
	`subscription_period` int(11) DEFAULT NULL,
	`type` varchar(255) DEFAULT NULL,
	`serialized_extra_info` text DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `membership_plan_packages` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`class_name` varchar(255) DEFAULT NULL,
	`membership_plan_sid` int(10) unsigned DEFAULT NULL,
	`fields` text DEFAULT NULL,
	`order` int(10) unsigned DEFAULT 0,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `membership_plan_listing_packages` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`package_sid` int(10) unsigned DEFAULT NULL,
	`listing_sid` int(10) unsigned DEFAULT NULL,
	`package_info` text DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `FK_listing_packages_listing` (`listing_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `core_settings` (`name`, `value`) VALUES
	('listing_and_subscription_notification_threshold', '1');

INSERT INTO `email_templates` (`id`, `subject`, `body`, `last_modified`) VALUES
('auto_extend_canceled', 'Auto Extend for Subscription on {$GLOBALS.site_url} Has Been Canceled', '<p>Dear {$user.username},</p>\r\n\r\n<p>Please be advised that automatic extension option of your subscription on {$GLOBALS.site_url} has been canceled. This occurred because the website administrator has changed terms and conditions of the Membership Plan you are currently subscribed to.</p>\r\n\r\n<p>Please log in to your Subscription page and re-enable automatic extension if you agree to the new terms and conditions.</p>\r\n\r\n<p>&nbsp;</p>\r\n', '2015-01-05 11:10:07'),
('contract_expired', 'Your Subscription on {$GLOBALS.site_url} Has Just Expired', '<p>&nbsp; &nbsp; &nbsp; &nbsp; Dear {$user.username},<br />\r\n&nbsp; &nbsp; &nbsp; &nbsp; Your subscription for listings on {$GLOBALS.site_url} has just expired. To renew it, please <a href="{$GLOBALS.site_url}/user/login/">log in</a>&nbsp;and go to the &quot;Subscription&quot; page.<br />\r\n&nbsp; &nbsp;&nbsp;<br />\r\n&nbsp; &nbsp; &nbsp; &nbsp;Administrator of {$GLOBALS.site_url}<br />\r\n&nbsp; &nbsp;&nbsp;</p>\r\n', '2015-01-05 10:28:54'),
('not_enough_funds_to_extend_contract', 'Not enough funds on {$GLOBALS.site_url} to extend subscription.', '<p>Dear {$user.username},<br />\r\nYour subscription on {$GLOBALS.site_url} has just expired due to not enough funds on your balance.<br />\r\n<br />\r\n{$GLOBALS.site_url} Administrator</p>\r\n', '2015-01-05 10:50:34'),
('listing_expiration_approaching_notification', '[[Listing Expiration Date Approaching]]', '{assign var="user_name" value=$user.username}{assign var="site_url" value=$GLOBALS.site_url}<p>[[Dear $user_name]],</p><p>[[Some of your listings posted at $site_url will expire in $numberOfDays days. To view them, please follow the link(s) below:]]</p>{foreach from=$listings item="listing"}<ul><li><a href="{$GLOBALS.site_url}/listing/{$listing.id}/">{$listing}</a></li></ul>{/foreach}<p>[[To keep your listings active, please enable Listing Re-Activation service for desired ads at <link>My Listings</link> page -> Manage Listing Options.]]</p><p>{$GLOBALS.site_url} Administrator</p>', '2015-01-05 10:49:06'),
('subscription_expiration_approaching_notification', '[[Subscription Expiration Date Approaching]]', '{assign var="user_name" value=$user.username}\r\n{assign var="site_url" value=$GLOBALS.site_url}\r\n<p>[[Dear $user_name]],</p>\r\n<p>[[Your subscription at $site_url will expire in $numberOfDays days.]]</p>\r\n<p>[[If you like to be able to post new listings and extend your expired ones, please subscribe to one of our Membership Plans at <link>Subscription</link> page.]]</p>\r\n<p>{$GLOBALS.site_url} Administrator</p>', '2015-01-05 10:52:29'),
('user_contract_extended', 'Your subscription on {$GLOBALS.site_url} successfully extended.', '<p>Dear {$user.username},<br />\r\nYour subscription on {$GLOBALS.site_url} successfully extended.</p>\r\n\r\n<p>{$GLOBALS.site_url} Administrator</p>\r\n', '2015-01-05 11:02:54');
