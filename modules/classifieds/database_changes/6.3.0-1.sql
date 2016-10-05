UPDATE `site_pages_pages` SET `template` = 'browsing_page.tpl' WHERE `uri` ='/browse-by-state/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `template` = 'browsing_page.tpl' WHERE `uri` ='/browse/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `template` = 'browsing_page.tpl' WHERE `uri` ='/browse/' AND `application_id` = 'MobileFrontEnd';

INSERT INTO `site_pages_pages` (`uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
('/sorry/', 0, 'main', 'display_template', '', '', 'FrontEnd', 'a:1:{s:13:"template_file";s:48:"classifieds^listing_not_found_or_deactivated.tpl";}', '', ''),
('/sorry/', 0, 'main', 'display_template', '', '', 'MobileFrontEnd', 'a:1:{s:13:"template_file";s:48:"classifieds^listing_not_found_or_deactivated.tpl";}', '', '');

ALTER TABLE `classifieds_categories` ADD `refine_search_template` varchar(250) DEFAULT NULL AFTER `search_template`;

UPDATE `classifieds_categories` SET `refine_search_template` = 'default_refine_search.tpl' WHERE `sid` = 0;
