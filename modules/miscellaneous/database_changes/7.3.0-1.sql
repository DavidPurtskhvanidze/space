UPDATE `site_pages_pages` set `id` = 'geographic_data' WHERE `uri` = '/geographic_data/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` set `id` = 'geographic_data_edit_location' WHERE `uri` = '/geographic_data/edit_location/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` set `id` = 'settings' WHERE `uri` = '/settings/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` set `id` = 'adminpswd' WHERE `uri` = '/adminpswd/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` set `id` = 'geographic_data_import_data' WHERE `uri` = '/geographic_data/import_data/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` set `id` = 'contact' WHERE `uri` = '/contact/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` set `id` = 'contact' WHERE `uri` = '/contact/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` set `id` = 'rate' WHERE `uri` = '/rate/' AND `application_id` = 'FrontEnd';

INSERT INTO `core_settings` (`name`, `value`) VALUES
('imageResourceProcessor', '\\modules\\miscellaneous\\lib\\image\\ImageResourceProcessor'),
('watermark_processor', '\\modules\\miscellaneous\\lib\\image\\ImageWatermarker');
