ALTER TABLE `payment_payments` ADD `invoice_sid` int(10) unsigned DEFAULT NULL AFTER `sid`;
ALTER TABLE `payment_payments` ADD `payment_gateway_id` varchar(255) DEFAULT NULL AFTER `last_updated`;
