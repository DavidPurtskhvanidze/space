INSERT INTO `custom_settings_settings` (`id`, `value`) VALUES
('number_of_category_columns','3'),
('listing_currency','$'),
('number_of_last_listings_for_rss','5'),
('color_for_highlighted_listing','#FFF0D1'),
('hidden_comments_background_color','#e9e9e9'),
('site_name','iLister'),
('transaction_currency','$'),
('uri_of_listing_details_page_on_mobile_frontend','/listing/'),
('copyright', null);
INSERT INTO `core_settings` (`name`, `value`) VALUES
('product_name', 'iLister'),
('changelog_url', 'http://www.worksforweb.com/classifieds-software/iLister/changelog/'),
('buy_now_link', 'https://billing.worksforweb.com/cart.php?gid=3'),
('google_play_link', 'https://play.google.com/store/apps/details?id=com.wfw.ilister');
UPDATE `site_pages_pages` SET `template` = 'home_page.tpl' WHERE (`application_id` = 'FrontEnd' AND `uri` = '/');
UPDATE `site_pages_pages` SET `template` = 'listing_view_page.tpl' WHERE (`application_id` = 'FrontEnd' AND `uri` = '/listing/');
UPDATE `site_pages_pages` SET `template` = 'search_result_page.tpl' WHERE (`application_id` = 'FrontEnd' AND `uri` = '/search-results/');
UPDATE `site_pages_pages` SET `template` = 'user_details_and_listings_page.tpl' WHERE (`application_id` = 'FrontEnd' AND `uri` = '/users/listings/');
UPDATE `site_pages_pages` SET `parameters` = 'a:1:{s:15:"priority_fields";s:12:"Title, Price";}' WHERE `uri` = '/compared-listings/';
