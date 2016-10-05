INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
  ('listing_fields', '/listing_fields/', NULL, 'classifieds', 'listing_fields', '', 'Listing Fields', 'AdminPanel', '', '', ''),
  ('add_listing_field', '/add_listing_field/', NULL, 'classifieds', 'add_listing_field', '', 'Add Listing Field', 'AdminPanel', '', '', ''),
  ('edit_listing_field', '/edit_listing_field/', NULL, 'classifieds', 'edit_listing_field', '', 'Edit Listing Field', 'AdminPanel', '', '', ''),
  ('categories', '/categories/', NULL, 'classifieds', 'categories', '', 'Categories', 'AdminPanel', '', '', ''),
  ('add_category', '/add_category/', NULL, 'classifieds', 'add_category', '', 'Add Category', 'AdminPanel', '', '', ''),
  ('add_category_bulk', '/add_category_bulk/', NULL, 'classifieds', 'add_category_bulk', '', 'Bulk Category Creation', 'AdminPanel', '', '', ''),
  ('edit_category', '/edit_category/', NULL, 'classifieds', 'edit_category', '', 'Manage Categories', 'AdminPanel', '', '', ''),
  ('delete_category', '/delete_category/', NULL, 'classifieds', 'delete_category', '', 'Delete Listing Type', 'AdminPanel', '', '', ''),
  ('add_category_field', '/add_category_field/', NULL, 'classifieds', 'add_category_field', '', 'Add Category Field', 'AdminPanel', '', '', ''),
  ('add_category_field_bulk', '/add_category_field_bulk/', NULL, 'classifieds', 'add_category_field_bulk', '', 'Add Category Field Bulk', 'AdminPanel', '', '', ''),
  ('edit_category_field', '/edit_category_field/', NULL, 'classifieds', 'edit_category_field', '', 'Edit Category Field', 'AdminPanel', '', '', ''),
  ('delete_category_field', '/delete_category_field/', NULL, 'classifieds', 'delete_category_field', '', 'Delete Category Field', 'AdminPanel', '', '', ''),
  ('add_listing', '/add_listing/', NULL, 'classifieds', 'add_listing', '', 'Add Listing', 'AdminPanel', '', '', ''),
  ('display_listing', '/display_listing/', NULL, 'classifieds', 'display_listing', '', 'Display Listing', 'AdminPanel', '', '', ''),
  ('manage_listings', '/manage_listings/', NULL, 'classifieds', 'manage_listings', '', 'Manage Listings', 'AdminPanel', '', '', ''),
  ('edit_listing', '/edit_listing/', NULL, 'classifieds', 'edit_listing', '', 'Edit Listing', 'AdminPanel', '', '', ''),
  ('edit_list', '/edit_list/', NULL, 'classifieds', 'edit_list', '', 'Edit List', 'AdminPanel', '', '', ''),
  ('edit_list_item', '/edit_list_item/', NULL, 'classifieds', 'edit_list_item', '', 'Edit List Item', 'AdminPanel', '', '', ''),
  ('edit_listing_field_edit_list', '/edit_listing_field/edit_list/', NULL, 'classifieds', 'edit_list', '', 'Edit List', 'AdminPanel', '', '', ''),
  ('edit_listing_field_edit_list_item', '/edit_listing_field/edit_list_item/', NULL, 'classifieds', 'edit_list_item', '', 'Edit List Item', 'AdminPanel', '', '', ''),
  ('edit_listing_field_edit_tree', '/edit_listing_field/edit_tree/', NULL, 'classifieds', 'edit_tree', '', 'Edit Tree', 'AdminPanel', '', '', ''),
  ('import_tree_data', '/import_tree_data/', NULL, 'classifieds', 'import_tree_data', '', 'Import Tree Data', 'AdminPanel', '', '', ''),

  ('listing_quick_view', '/listing/quick_view/', 1, 'classifieds', 'quick_view_listing', '', 'Quick View', 'FrontEnd', '', '', ''),
  ('listing_add', '/listing/add/', NULL, 'classifieds', 'add_listing', 'listing_add.tpl', 'Add Listing', 'FrontEnd', '', '', ''),
  ('search', '/search/', 1, 'classifieds', 'search_form', 'search.tpl', 'Search Listings', 'FrontEnd', 'N;', '', ''),
  ('user_listings', '/user/listings/', 0, 'classifieds', 'my_listings', 'user_listings.tpl', 'My Listings', 'FrontEnd', 'N;', '', ''),
  ('listing', '/listing/', 1, 'classifieds', 'display_listing', '', 'Listing Details', 'FrontEnd', 'N;', '', ''),
  ('search_results', '/search-results/', 0, 'classifieds', 'search_results', 'search_result_page.tpl', 'Search Results', 'FrontEnd', 'a:4:{s:21:\"default_sorting_field\";s:15:\"activation_date\";s:21:\"default_sorting_order\";s:4:\"DESC\";s:25:\"default_listings_per_page\";s:2:\"20\";s:24:\"advanced_search_form_uri\";s:8:\"/search/\";}', '', ''),
  ('listing_edit', '/listing/edit/', 1, 'classifieds', 'edit_listing', 'listing_edit.tpl', 'Edit Listing', 'FrontEnd', 'N;', '', ''),
  ('print_listing', '/print-listing/', 0, 'classifieds', 'display_listing', 'print.tpl', '', 'FrontEnd', 'N;', '', ''),
  ('listing_save', '/listing/save/', NULL, 'classifieds', 'save_listing', 'blank.tpl', 'Saved Ads', 'FrontEnd', 'N;', '', ''),
  ('tell_friends', '/tell-friends/', 0, 'classifieds', 'tell_friend', 'blank.tpl', '', 'FrontEnd', 'N;', '', ''),
  ('loan_calculator', '/loan-calculator/', 0, 'classifieds', 'loan_calculator', 'blank.tpl', '', 'FrontEnd', 'N;', '', ''),
  ('contact_seller', '/contact-seller/', 0, 'classifieds', 'contact_seller', 'blank.tpl', '', 'FrontEnd', 'N;', '', ''),
  ('search_save', '/search/save/', NULL, 'classifieds', 'save_search', 'blank.tpl', '', 'FrontEnd', 'N;', '', ''),
  ('video_player', '/video-player/', 0, 'classifieds', 'display_listing', 'blank.tpl', '', 'FrontEnd', 'a:1:{s:16:\"display_template\";s:16:\"video_player.tpl\";}', '', ''),
  ('browse', '/browse/', 1, 'classifieds', 'browse', 'browsing_page.tpl', 'Categories', 'FrontEnd', 'a:3:{s:11:\"category_id\";s:4:\"root\";s:6:\"fields\";s:12:\"category_sid\";s:14:\"number_of_cols\";s:1:\"3\";}', '', ''),
  ('compared_listings', '/compared-listings/', 0, 'classifieds', 'compared_listings', 'blank.tpl', '', 'FrontEnd', 'N;', '', ''),
  ('listing_compare', '/listing/compare/', NULL, 'classifieds', 'add_to_comparison', 'blank.tpl', '', 'FrontEnd', 'N;', '', ''),
  ('browse_by_state', '/browse-by-state/', 1, 'classifieds', 'browse', 'browsing_page.tpl', 'States', 'FrontEnd', 'a:3:{s:11:\"category_id\";s:4:\"root\";s:6:\"fields\";s:5:\"State\";s:14:\"number_of_cols\";s:1:\"3\";}', '', ''),
  ('user_saved_listings', '/user/saved-listings/', 0, 'classifieds', 'saved_listings', 'user_saved_listings.tpl', 'Saved Listings', 'FrontEnd', 'N;', '', ''),
  ('user_saved_searches', '/user/saved-searches/', 0, 'classifieds', 'saved_searches', '', 'Saved Searches', 'FrontEnd', 'N;', '', ''),
  ('listing_make_featured', '/listing/make-featured/', 0, 'classifieds', 'make_featured', '', 'Make Featured', 'FrontEnd', 'N;', '', ''),
  ('book_listing', '/book-listing/', 0, 'classifieds', 'manage_calendar', '', 'Book', 'FrontEnd', 'a:1:{s:13:\"show_template\";s:16:\"book_listing.tpl\";}', '', ''),
  ('manage_calendar', '/manage-calendar/', 0, 'classifieds', 'manage_calendar', '', '', 'FrontEnd', 'a:0:{}', '', ''),
  ('listing_pictures', '/listing/pictures/', 1, 'classifieds', 'display_listing', '', 'Listing Pictures', 'FrontEnd', 'a:1:{s:16:\"display_template\";s:22:\"listing_all_images.tpl\";}', '', ''),
  ('sorry', '/sorry/', 0, 'main', 'display_template', '', '', 'FrontEnd', 'a:1:{s:13:"template_file";s:48:"classifieds^listing_not_found_or_deactivated.tpl";}', '', ''),
  ('listings_featured', '/listings/featured/', 0, 'main', 'display_template', 'search.tpl', 'Featured Listings', 'FrontEnd', 'a:1:{s:13:"template_file";s:21:"listings_featured.tpl";}', '', ''),
  ('listings_popular', '/listings/popular/', 0, 'main', 'display_template', 'search.tpl', 'Popular Listings', 'FrontEnd', 'a:1:{s:13:"template_file";s:20:"listings_popular.tpl";}', '', ''),
  ('listings_recent', '/listings/recent/', 0, 'main', 'display_template', 'search.tpl', 'Recent listings', 'FrontEnd', 'a:1:{s:13:"template_file";s:19:"listings_recent.tpl";}', '', ''),

  ('search', '/search/', 1, 'classifieds', 'search_form', '', 'Search Listings', 'MobileFrontEnd', 'a:0:{}', '', ''),
  ('listing', '/listing/', 1, 'classifieds', 'display_listing', '', 'Listing Details', 'MobileFrontEnd', 'N;', '', ''),
  ('search_results', '/search-results/', 0, 'classifieds', 'search_results', '', 'Search Results', 'MobileFrontEnd', 'a:4:{s:21:\"default_sorting_field\";s:15:\"activation_date\";s:21:\"default_sorting_order\";s:4:\"DESC\";s:25:\"default_listings_per_page\";s:2:\"10\";s:24:\"advanced_search_form_uri\";s:8:\"/search/\";}', '', ''),
  ('browse', '/browse/', 1, 'classifieds', 'browse', 'browsing_page.tpl', 'Categories', 'MobileFrontEnd', 'a:3:{s:11:\"category_id\";s:4:\"root\";s:6:\"fields\";s:12:\"category_sid\";s:25:\"default_listings_per_page\";s:2:\"10\";}', '', ''),
  ('listing_map', '/listing/map/', 1, 'classifieds', 'display_listing', '', '', 'MobileFrontEnd', 'a:1:{s:16:\"display_template\";s:42:\"category_templates/display/display_map.tpl\";}', '', ''),
  ('listing_pictures', '/listing/pictures/', 1, 'classifieds', 'display_listing', '', '', 'MobileFrontEnd', 'a:1:{s:16:\"display_template\";s:47:\"category_templates/display/display_pictures.tpl\";}', '', ''),
  ('listing_seller_info', '/listing/seller-info/', 1, 'classifieds', 'display_listing', '', '', 'MobileFrontEnd', 'a:1:{s:16:\"display_template\";s:50:\"category_templates/display/display_seller_info.tpl\";}', '', ''),
  ('tell_a_friend', '/tell-a-friend/', 0, 'classifieds', 'tell_friend', '', 'Tell a Friend', 'MobileFrontEnd', 'a:0:{}', '', ''),
  ('contact_seller', '/contact-seller/', 0, 'classifieds', 'contact_seller', '', '', 'MobileFrontEnd', 'a:0:{}', '', ''),
  ('listing_picture', '/listing/picture/', 1, 'classifieds', 'display_listing', '', '', 'MobileFrontEnd', 'a:1:{s:16:\"display_template\";s:46:\"category_templates/display/display_picture.tpl\";}', '', ''),
  ('listing_save', '/listing/save/', 0, 'classifieds', 'save_listing', '', 'Save Ad', 'MobileFrontEnd', 'a:0:{}', '', ''),
  ('listing_video', '/listing/video/', 1, 'classifieds', 'display_listing', '', '', 'MobileFrontEnd', 'a:1:{s:16:\"display_template\";s:44:\"category_templates/display/display_video.tpl\";}', '', ''),
  ('user_saved_listings', '/user/saved-listings/', 0, 'classifieds', 'saved_listings', '', 'Saved Listings', 'MobileFrontEnd', 'a:0:{}', '', ''),
  ('browse_by_state_and_categories', '/browse-by-state-and-categories/', 1, 'classifieds', 'browse', 'browsing_page.tpl', 'States and Categories', 'FrontEnd', 'a:5:{s:11:\"category_id\";s:4:\"root\";s:6:\"fields\";s:33:\"State, category_sid, category_sid\";s:16:\"number_of_levels\";s:1:\"3\";s:14:\"number_of_cols\";s:1:\"3\";s:15:\"browse_template\";s:34:\"browse_by_state_and_categories.tpl\";}', '', ''),
  ('sorry', '/sorry/', 0, 'main', 'display_template', '', '', 'MobileFrontEnd', 'a:1:{s:13:"template_file";s:48:"classifieds^listing_not_found_or_deactivated.tpl";}', '', '');

UPDATE `site_pages_pages` SET `no_index` = 1 WHERE `uri` = '/search-results/';

CREATE TABLE IF NOT EXISTS `classifieds_categories` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id` varchar(255) DEFAULT NULL,
	`parent` int(10) unsigned DEFAULT NULL,
	`order` int(10) unsigned DEFAULT NULL,
	`name` varchar(250) DEFAULT NULL,
	`input_template` varchar(250) DEFAULT NULL,
	`search_template` varchar(250) DEFAULT NULL,
	`refine_search_template` varchar(250) DEFAULT NULL,
	`search_result_template` varchar(250) DEFAULT NULL,
	`view_template` varchar(250) DEFAULT NULL,
	`browsing_settings` text,
	`listing_caption_template_content` varchar(1024) DEFAULT NULL,
	`last_modified` datetime DEFAULT NULL,
	`listing_url_seo_data` varchar(255) DEFAULT NULL,
  `meta_keywords` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `meta_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `page_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	`active_listing_number` INT DEFAULT 0,
	`listing_number` INT DEFAULT 0,
	PRIMARY KEY (`sid`),
	KEY `id_index` (`id`),
	KEY `parent_fk` (`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `classifieds_categories` (`sid`, `id`, `parent`, `order`, `name`, `input_template`, `search_template`, `refine_search_template`, `search_result_template`, `view_template`, `browsing_settings`, `listing_caption_template_content`, `last_modified`, `listing_url_seo_data`) VALUES
(0, 'root', NULL, NULL, 'Categories', 'default_input.tpl', 'default_search.tpl',  'default_refine_search.tpl', 'default_search_result_item.tpl', 'default_view.tpl', NULL, '<span class=\"fieldValue fieldValueTitle\">{\$listing.Title}</span>', '2010-10-29 14:31:23', '{\$listing.Title}');

UPDATE `classifieds_categories` SET `sid` = 0 WHERE `id` = 'root';

CREATE TABLE IF NOT EXISTS `classifieds_listings` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`category_sid` int(10) unsigned NOT NULL DEFAULT '0',
	`user_sid` int(10) unsigned DEFAULT NULL,
	`active` tinyint(4) unsigned NOT NULL DEFAULT '0',
	`moderation_status` varchar(255) DEFAULT NULL,
	`keywords` text DEFAULT NULL,
	`views` int(11) NOT NULL DEFAULT '0',
	`pictures` int(10) unsigned DEFAULT NULL,
	`activation_date` datetime DEFAULT NULL,
	`expiration_date` datetime DEFAULT NULL,
	`first_activation_date` datetime DEFAULT NULL,
	`last_user_ip` varchar(255) DEFAULT NULL,
  `meta_keywords` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `meta_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `page_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
	PRIMARY KEY (`sid`),
  KEY `category_sid` (`category_sid`),
  KEY `user_sid` (`user_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `classifieds_listings_pictures` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`listing_sid` int(10) unsigned DEFAULT NULL,
	`storage_method` varchar(255) DEFAULT NULL,
	`picture_url` text DEFAULT NULL,
	`order` int(10) DEFAULT NULL,
	`caption` varchar(255) DEFAULT NULL,
  `pictures_file_size` INT DEFAULT NULL,
  `pictures_file_name` varchar(255) DEFAULT NULL,
  `pictures_content_type` varchar(15) DEFAULT NULL,
	PRIMARY KEY (`sid`),
  KEY `listing_sid` (`listing_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `classifieds_listing_fields` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`id` varchar(255) DEFAULT NULL,
	`category_sid` int(10) unsigned NULL DEFAULT '0',
	`caption` varchar(250) DEFAULT NULL,
	`type` varchar(250) DEFAULT NULL,
	`is_required` varchar(250) DEFAULT NULL,
	`maxlength` varchar(250) DEFAULT NULL,
	`minimum` varchar(250) DEFAULT NULL,
	`maximum` varchar(250) DEFAULT NULL,
	`signs_num` varchar(250) DEFAULT NULL,
	`levels_ids` varchar(250) DEFAULT NULL,
	`levels_captions` varchar(250) DEFAULT NULL,
	`max_file_size` varchar(250) DEFAULT NULL,
	PRIMARY KEY (`sid`),
  KEY `category_sid` (`category_sid`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `classifieds_listing_fields_order` (
  `category_sid` int(10) unsigned NOT NULL DEFAULT '0',
  `field_sid` int(10) unsigned NOT NULL DEFAULT '0',
  `order` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`category_sid`,`field_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `classifieds_listing_field_calendar` (
	`sid` int(11) NOT NULL AUTO_INCREMENT,
	`from` date DEFAULT NULL,
	`to` date DEFAULT NULL,
	`status` varchar(50) DEFAULT NULL,
	`field_sid` int(11) DEFAULT NULL,
	`listing_sid` int(11) DEFAULT NULL,
	`comment` text DEFAULT NULL ,
	PRIMARY KEY (`sid`),
	KEY `field_sid` (`field_sid`),
	KEY `listing_sid` (`listing_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `classifieds_listing_field_list` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`field_sid` int(10) unsigned DEFAULT NULL,
	`order` int(10) unsigned DEFAULT NULL,
	`rank` int(10) unsigned DEFAULT NULL,
	`value` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`sid`),
  KEY `field_sid` (`field_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `classifieds_listing_field_tree` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`field_sid` int(10) unsigned DEFAULT NULL,
	`parent_sid` int(10) unsigned DEFAULT NULL,
	`level` int(10) unsigned DEFAULT NULL,
	`order` int(10) unsigned DEFAULT NULL,
	`caption` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`sid`),
  KEY `parent_sid` (`parent_sid`),
  KEY `field_sid` (`field_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `classifieds_saved_searches` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`user_sid` int(10) unsigned DEFAULT NULL,
	`name` varchar(255) DEFAULT NULL,
	`data` text DEFAULT NULL,
	`auto_notify` tinyint(3) unsigned DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `classifieds_saved_listings` (
	`user_sid` int(10) unsigned DEFAULT NULL,
	`listings` text DEFAULT NULL,
	KEY `user_sid` (`user_sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `classifieds_rating` (
	`object_sid` int(11) NOT NULL,
	`object_type` varchar(50) NOT NULL DEFAULT '',
	`field_sid` int(11) DEFAULT NULL,
	`rating` float DEFAULT NULL,
	`user_sid` int(11) NOT NULL DEFAULT 0,
	PRIMARY KEY (`object_sid`,`field_sid`,`user_sid`,`object_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `core_settings` (`name`, `value`) VALUES
	('notify_on_listing_added', '1'),
	('notify_on_listing_expiration', '1');

CREATE TABLE IF NOT EXISTS `classifieds_feature_display_rotator` (
	`listing_sid` int(10) NOT NULL DEFAULT '0',
	`feature_type` varchar(64) NOT NULL DEFAULT '',
	`activation_date` datetime DEFAULT NULL,
	`expiration_date` datetime DEFAULT NULL,
	`order` int(11) unsigned DEFAULT NULL,
	PRIMARY KEY (`listing_sid`,`feature_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `email_templates` (`id`, `subject`, `body`, `last_modified`) VALUES
('admin_add_listing_email', '{$GLOBALS.site_url}: New Listing Has Just Been Added', '<p>Dear Admin,&nbsp; &nbsp;&nbsp;<br />\r\nA new listing was added to your website at {$GLOBALS.site_url}. Here are the details:</p>\r\n\r\n<p>&nbsp; &nbsp;&nbsp;Listing ID: {$listing.id} &quot;{$listing}&quot;<br />\r\n&nbsp;&nbsp; &nbsp;Added by: &nbsp;{$user.username}<br />\r\n&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;{$GLOBALS.site_url} Administrator</p>\r\n', '2014-12-30 15:15:30'),
('admin_report_improper_listing_content', 'Improper Content of the Listing #{$listing.sid} on {$GLOBALS.site_url}', '<p>Hello,<br />\r\nThe visitor {$formData.FullName} (email {$formData.Email}) of your site {$GLOBALS.site_url} reported an improper content&nbsp;<br />\r\nof the <a href="{$admin_site_url}/display_listing/?listing_id={$listing.sid}">{$listing}</a>:<br />\r\n{$formData.Report}<br />\r\nYou may want to <a href="{$admin_site_url}/edit_listing/?listing_id={$listing.sid}">edit the listing</a>&nbsp;or&nbsp;<a href="{$admin_site_url}/system/classifieds/listing_actions/?action=Deactivate&amp;listings[{$listing.sid}]=1&amp;searchId=&amp;returnBackUri=%2Fdisplay_listing%2F%3FsearchId%3D%26listing_id%3D{$listing.sid}">deactivate</a>.</p>\r\n', '2015-04-30 18:04:01'),
('book_request', 'New Booking Request On {$GLOBALS.site_url} For the Listing #{$listing.sid}', '<p>Dear {if $listing.user_sid.value == 0}Admin{else}{$listing.user.FirstName}{/if},</p>\r\n<p>You have received a new booking request on {$GLOBALS.site_url} for the following listing of yours:</p>\r\n<p><a href="{$GLOBALS.site_url}/listing/{$listing.id}/">#{$listing.id} "{$listing}"</a></p>\r\n<p>Here are the booking details:</p>\r\n<p>Sender`s Name: {$sender_name}<br />\r\n   Sender`s Email: {$sender_email}<br />\r\n   Booking from {$period_start} to {$period_end}<br />\r\n   Comments and Instructions:<br />\r\n   {$comment}</p>\r\n\r\n{capture name=message}\r\n    Sender`s Name: {$sender_name}<br />\r\n    Sender`s Email: {$sender_email}<br />\r\n    Booking from {$period_start} to {$period_end}<br />\r\n    Comments and Instructions:<br />\r\n    {$comment}\r\n{/capture}\r\n\r\n<p>\r\n    <br />\r\n    To perform the reservation for this property, please go to our Manage Calendar area by clicking the link below:<br />\r\n    <a href="{$GLOBALS.site_url}/manage-calendar/?action=preview_book&listing_sid={$listing.id}&field_sid={$field_sid}&from={$period_start}&to={$period_end}&comment={$smarty.capture.message|escape:"url"}">Manage Calendar</a><br />\r\n    <br />\r\n     {$GLOBALS.site_url} Administrator\r\n</p>\r\n', '2015-12-08 11:42:27'),
('contact_seller', 'Request for Additional Information About Listing #{$listing.id} on {$GLOBALS.site_url}', '<p>Dear {$listing.user.username},&lt;br /&gt;&lt;br /&gt;<br />\r\n&nbsp;&nbsp; &nbsp;One of the visitors of <a href="{$GLOBALS.site_url}">{$GLOBALS.site_url}</a>&nbsp;would like to contact you for additional information about your listing #{$listing.id} &quot;{$listing}&quot;:<br />\r\n&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;Listing URL: <a href="{$GLOBALS.site_url}/listing/{$listing.id}/">{$GLOBALS.site_url}/listing/{$listing.id}/</a><br />\r\n&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;Here is the information the contacting person requested, along with their user details:&nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;Contact&#39;s Name: {$seller_request.FullName}<br />\r\n&nbsp;&nbsp; &nbsp;Contact&#39;s Email: {$seller_request.Email}<br />\r\n&nbsp;&nbsp; &nbsp;Comments: {$seller_request.Request}<br />\r\n&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;Best regards,<br />\r\n&nbsp;&nbsp; &nbsp;Administrator,<br />\r\n&nbsp; &nbsp; <a href="{$GLOBALS.site_url}">{$GLOBALS.site_url}</a></p>\r\n', '2014-12-30 15:24:34'),
('expired_listings_report', 'Listings expiration/extension report from {$GLOBALS.site_url}', '<p>Dear {$user.username},<br />\r\n<br />\r\nThis email contains information regarding your listings extension and expiration on <a href="{$GLOBALS.site_url}">{$GLOBALS.site_url}</a>.<br />\r\n&nbsp;</p>\r\n\r\n<p>The following listings have expired:</p>\r\n\r\n<p>{foreach from=$expiredListings item=listing}</p>\r\n\r\n<ul>\r\n	<li><strong>{$listing.id} &quot;{$listing}&quot;</strong></li>\r\n</ul>{/foreach}\r\n\r\n<p>&nbsp; &nbsp; Best regards,<br />\r\n&nbsp; &nbsp; Administrator of <a href="{$GLOBALS.site_url}/">{$GLOBALS.site_url}</a><br />\r\n&nbsp;</p>\r\n', '2014-12-30 15:44:52'),
('listing_activation', 'Listing #{$listing_sid} activated', '<p>Dear {$user.username},<br />\r\n<br />\r\nYour listing <a href="{$user_site_url}/listing/{$listing_sid}/">#{$listing_sid}</a>&nbsp;&quot;<a href="{$user_site_url}/listing/{$listing_sid}/">{$listing}</a>&quot; has been successfully activated on the website <a href="{$user_site_url}">{$user_site_url}</a>.<br />\r\n<br />\r\n<a href="{$user_site_url}">{$user_site_url}</a>&nbsp; &nbsp;Administration.</p>\r\n\r\n<p>&nbsp;</p>\r\n', '2014-12-30 16:04:32'),
('listing_rejected', '{$user_site_url}: Listing {$listing.id} Has Been Rejected', '<p>Dear {if $listing.user.value == 0}Admin{else}{$listing.user.username}{/if},<br />\r\n<br />\r\nYour listing #{$listing.id} &quot;{$listing}&quot; has been rejected. The most probable reason for that would be that your listing contains unauthorized materials or violates our website&#39;s Terms of Use, or in any other way does not meet the acceptable listing content criteria. Please contact the <a href="mailto:{$GLOBALS.notification_email}">Administrator</a>&nbsp;for specific inquiries.</p>\r\n\r\n<p>&nbsp;</p>\r\n', '2014-12-30 16:06:05'),
('saved_search_notification', 'New Listings For "{$saved_search.name}" on {$GLOBALS.site_url} Matching Your Search Criteria', '<p>[[Dear {$user.username}]],<br />\r\n[[We have new listing(s) that match your saved search criteria. To view them, please follow the link(s) below:]]<br />\r\n{foreach from=$listings item="listing"}</p>\r\n\r\n<ul>\r\n	<li><a href="{$GLOBALS.site_url}/listing/{$listing.id}/">{$listing}</a>&nbsp; &nbsp;&nbsp;</li>\r\n</ul>\r\n{/foreach}\r\n<p><span style="line-height:1.6em">{$GLOBALS.site_url} Administrator</span></p>\r\n', '2015-01-21 12:09:05'),
('tell_friend', 'Recommendation Regarding Listing #{$listing.sid} on {$GLOBALS.site_url}', '<p>Dear {$submitted_data.friend_name},<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Someone, most likely your friend or person you know {$submitted_data.name}, visited our website at <a href="{$GLOBALS.site_url}">{$GLOBALS.site_url}</a>&nbsp;and decided that you should take a look at this listing:<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Listing ID: {$listing.id} &quot;{$listing}&quot;<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Listing URL: <a href="{$GLOBALS.site_url}/listing/{$listing.id}/">{$GLOBALS.site_url}/listing/{$listing.id}/</a><br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Posted by: {if $listing.user_sid.value == 0}Administrator{else}{$listing.user.username}{/if} on {$listing.activation_date}<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Recommended by: {$submitted_data.name}. &nbsp; &nbsp; &nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Message text: {$submitted_data.comment}<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;If you don&#39;t know this person, please simply disregard this email.<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Best regards,<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;Administrator,<br />\r\n&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; <a href="{$GLOBALS.site_url}">{$GLOBALS.site_url}</a></p>\r\n', '2014-12-30 15:54:04'),
('admin_edit_listing_pending_email', '{$GLOBALS.site_url}: Listing Has Just Been Modified', '<p>Dear Admin,&nbsp; &nbsp;&nbsp;<br />\r\nListing {$listing.id}&nbsp;was modified on your website at {$GLOBALS.site_url}.</p>\r\n\r\n<p>Here are the details:</p>\r\n\r\n<p>&nbsp; &nbsp;&nbsp;Listing ID: {$listing.id} <a href="{$GLOBALS.site_url}/admin/display_listing/?listing_id={$listing.id}">"{$listing}"</a><br />\r\n&nbsp;&nbsp; &nbsp;Modified by: &nbsp;{$user.username}<br />\r\n&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;{$GLOBALS.site_url} Administrator</p>\r\n', '2016-05-31 18:37:04');
