ALTER TABLE  `site_pages_pages` ADD `id` varchar(255) NOT NULL;

UPDATE `site_pages_pages` SET `id` = 'site_pages' WHERE `uri` = '/site_pages/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'mobile_site_pages' WHERE `uri` = '/mobile_site_pages/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'subdomain_site_pages' WHERE `uri` = '/subdomain_site_pages/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'root' WHERE `uri` = '/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'root' WHERE `uri` = '/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'root' WHERE `uri` = '/' AND `application_id` = 'MobileFrontEnd';
