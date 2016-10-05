UPDATE `site_pages_pages` SET `template` = 'home_page.tpl' WHERE (`application_id` = 'FrontEnd' AND `uri` = '/');
UPDATE `site_pages_pages` SET `template` = 'listing_view_page.tpl' WHERE (`application_id` = 'FrontEnd' AND `uri` = '/listing/');
UPDATE `site_pages_pages` SET `template` = 'search_result_page.tpl' WHERE (`application_id` = 'FrontEnd' AND `uri` = '/search-results/');
UPDATE `site_pages_pages` SET `template` = 'user_details_and_listings_page.tpl' WHERE (`application_id` = 'FrontEnd' AND `uri` = '/users/listings/');
