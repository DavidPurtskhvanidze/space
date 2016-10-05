UPDATE `site_pages_pages` SET `template` = 'contact_us_page.tpl' WHERE `uri` ='/contact/' AND `application_id` = 'FrontEnd';
INSERT INTO `core_settings` (`name`, `value`) VALUES
('autocomplete_ajax_data_encription_key', CONCAT(PASSWORD(RAND()),PASSWORD(RAND())));
