UPDATE `site_pages_pages` SET `id` = 'payment_callback' WHERE `uri` = '/callback/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'user_payments', `template`='user_payments.tpl' WHERE `uri` = '/user/payments/' AND `application_id` = 'FrontEnd';
