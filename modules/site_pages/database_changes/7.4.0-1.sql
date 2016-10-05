ALTER TABLE  `site_pages_pages` ADD `no_index` tinyint(4) NOT NULL DEFAULT 0;

UPDATE `site_pages_pages` SET `title` = 'Classifieds Script' WHERE `uri` = '/';

INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
('manage_mobile_forms', '/mobile_manage_forms/', 1, 'form_manager', 'manage_forms', '', '', 'AdminPanel', 'a:1:{s:14:\"application_id\";s:14:\"MobileFrontEnd\";}', '', '');

