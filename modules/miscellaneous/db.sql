INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
  ('geographic_data', '/geographic_data/', NULL, 'miscellaneous', 'geographic_data', '', 'Geographic Data', 'AdminPanel', '', '', ''),
  ('geographic_data_edit_location', '/geographic_data/edit_location/', NULL, 'miscellaneous', 'edit_location', '', 'Edit Location', 'AdminPanel', '', '', ''),
  ('settings', '/settings/', NULL, 'miscellaneous', 'settings', '', '', 'AdminPanel', '', '', ''),
  ('adminpswd', '/adminpswd/', NULL, 'miscellaneous', 'adminpswd', '', 'Admin Password', 'AdminPanel', '', '', ''),
  ('geographic_data_import_data', '/geographic_data/import_data/', NULL, 'miscellaneous', 'import_geographic_data', '', 'Import Geographic Data', 'AdminPanel', '', '', ''),
  ('contact', '/contact/', 0, 'miscellaneous', 'contact_form', 'contact_us_page.tpl', 'Contact Info', 'FrontEnd', 'N;', '', ''),
  ('rate', '/rate/', NULL, 'miscellaneous', 'rate', '', '', 'FrontEnd', '', '', ''),
  ('contact', '/contact/', 0, 'miscellaneous', 'contact_form', '', 'Contact Info', 'MobileFrontEnd', 'a:0:{}', '', '');

INSERT INTO `core_settings` (`name`, `value`) VALUES
	('system_email_reply_to', 'blackhole@worksforweb.com'),
	('system_email_return_path', 'blackhole@worksforweb.com'),
	('main_logo', NULL),
	('fixed_top_menu_logo', NULL),
	('mobile_logo', NULL),
	('under_construction_mode', '0'),
	('autocomplete_ajax_data_encription_key', CONCAT(PASSWORD(RAND()),PASSWORD(RAND()))),
  ('imageResourceProcessor', '\\modules\\miscellaneous\\lib\\image\\ImageResourceProcessor'),
  ('watermark_processor', '\\modules\\miscellaneous\\lib\\image\\ImageWatermarker');

INSERT INTO `email_templates` (`id`, `subject`, `body`, `last_modified`) VALUES
('admin_contact_form_message', 'Comments From {$name} On {$GLOBALS.site_url}', '|| name: {$name}<br/>|| email: {$email}<br/>|| comments: <br />{$comments}<br/>', '2015-01-05 11:32:13'),
('admin_expired_contracts_and_listings_report', '{if $expiredContractsLog|@count && $expiredListingsLog|@count} {$GLOBALS.site_url}: Expired Contracts And Expired Listings Report {elseif $expiredContractsLog|@count} {$GLOBALS.site_url}: Expired Contracts Report {elseif $expiredListingsLog|@count} {$GLOBALS.site_url}: Expired Listings Report {/if}', '<p><br />\r\n{if $expiredContractsLog|@count}<br />\r\n<strong>User With Expired Contracts</strong><br />\r\n{foreach from=$expiredContractsLog item=expiredContractLog}<br />\r\n<strong>{$expiredContractLog.user.username}</strong>&nbsp;{$expiredContractLog.user.email}<br />\r\n<span style="line-height:1.6em">{/foreach}</span><br />\r\n<span style="line-height:1.6em">{/if}</span></p>\r\n\r\n<p>{if $expiredListingsLog|@count}<br />\r\n<strong>Expired Listings by Users</strong><br />\r\n{foreach from=$expiredListingsLog item=expiredUserListingsLog}<br />\r\n<strong>{$expiredUserListingsLog.username} listings</strong><br />\r\nExpired listings: {foreach from=$expiredUserListingsLog.expiredListingsSid item=listingSid}{$listingSid} {/foreach}<br />\r\n{/foreach}<br />\r\n{/if}</p>\r\n', '2015-01-05 11:35:46');
