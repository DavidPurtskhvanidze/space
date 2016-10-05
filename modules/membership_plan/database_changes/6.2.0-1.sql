ALTER TABLE `membership_plan_contracts` CHANGE `price`  `price` DECIMAL(30, 2) UNSIGNED DEFAULT NULL;
ALTER TABLE `membership_plan_plans` CHANGE `price`  `price` DECIMAL(30, 2) DEFAULT NULL;
INSERT INTO `core_settings` (`name`, `value`) VALUES ('listing_and_subscription_notification_threshold', '1');
