ALTER TABLE `classifieds_listings_pictures` CHANGE COLUMN `thumb_saved_name` `thumbnail_saved_name` varchar(255) DEFAULT NULL;

UPDATE `site_pages_pages` SET `id` = 'listing_fields' WHERE `uri` = '/listing_fields/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'add_listing_field' WHERE `uri` = '/add_listing_field/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_listing_field' WHERE `uri` = '/edit_listing_field/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'categories' WHERE `uri` = '/categories/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'add_category' WHERE `uri` = '/add_category/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'add_category_bulk' WHERE `uri` = '/add_category_bulk/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_category' WHERE `uri` = '/edit_category/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'delete_category' WHERE `uri` = '/delete_category/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'add_category_field' WHERE `uri` = '/add_category_field/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'add_category_field_bulk' WHERE `uri` = '/add_category_field_bulk/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_category_field' WHERE `uri` = '/edit_category_field/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'delete_category_field' WHERE `uri` = '/delete_category_field/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'add_listing' WHERE `uri` = '/add_listing/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'display_listing' WHERE `uri` = '/display_listing/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'manage_listings' WHERE `uri` = '/manage_listings/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_listing' WHERE `uri` = '/edit_listing/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'user_listings', `template` = 'user_listings.tpl' WHERE `uri` = '/user/listings/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'edit_list' WHERE `uri` = '/edit_list/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_list_item' WHERE `uri` = '/edit_list_item/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_listing_field_edit_list' WHERE `uri` = '/edit_listing_field/edit_list/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_listing_field_edit_list_item' WHERE `uri` = '/edit_listing_field/edit_list_item/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'edit_listing_field_edit_tree' WHERE `uri` = '/edit_listing_field/edit_tree/' AND `application_id` = 'AdminPanel';
UPDATE `site_pages_pages` SET `id` = 'import_tree_data' WHERE `uri` = '/import_tree_data/' AND `application_id` = 'AdminPanel';

UPDATE `site_pages_pages` SET `id` = 'listing_add', `template` = 'listing_add.tpl' WHERE `uri` = '/listing/add/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'search', `template` = 'search.tpl' WHERE `uri` = '/search/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing_quick_view' WHERE `uri` = '/listing/quick_view/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'search' WHERE `uri` = '/search/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing' WHERE `uri` = '/listing/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'search_results', `template` = 'search_result_page.tpl' WHERE `uri` = '/search-results/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing_edit', `template` = 'listing_edit.tpl' WHERE `uri` = '/listing/edit/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'print_listing' WHERE `uri` = '/print-listing/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing_save' WHERE `uri` = '/listing/save/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'tell_friends' WHERE `uri` = '/tell-friends/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'loan_calculator' WHERE `uri` = '/loan-calculator/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'contact_seller' WHERE `uri` = '/contact-seller/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'search_save' WHERE `uri` = '/search/save/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'video_player' WHERE `uri` = '/video-player/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'browse' WHERE `uri` = '/browse/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'compared_listings' WHERE `uri` = '/compared-listings/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing_compare' WHERE `uri` = '/listing/compare/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'browse_by_state' WHERE `uri` = '/browse-by-state/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'user_saved_listings', `template` = 'user_saved_listings.tpl' WHERE `uri` = '/user/saved-listings/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'user_saved_searches' WHERE `uri` = '/user/saved-searches/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing_make_featured' WHERE `uri` = '/listing/make-featured/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'book_listing' WHERE `uri` = '/book-listing/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'manage_calendar' WHERE `uri` = '/manage-calendar/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing_pictures' WHERE `uri` = '/listing/pictures/' AND `application_id` = 'FrontEnd';
UPDATE `site_pages_pages` SET `id` = 'sorry' WHERE `uri` = '/sorry/' AND `application_id` = 'FrontEnd';

UPDATE `site_pages_pages` SET `id` = 'search' WHERE `uri` = '/search/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing' WHERE `uri` = '/listing/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'search_results' WHERE `uri` = '/search-results/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'browse' WHERE `uri` = '/browse/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing_map' WHERE `uri` = '/listing/map/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing_pictures' WHERE `uri` = '/listing/pictures/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing_seller_info' WHERE `uri` = '/listing/seller-info/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'tell_a_friend' WHERE `uri` = '/tell-a-friend/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'contact_seller' WHERE `uri` = '/contact-seller/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing_picture' WHERE `uri` = '/listing/picture/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing_save' WHERE `uri` = '/listing/save/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'listing_video' WHERE `uri` = '/listing/video/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'user_saved_listings' WHERE `uri` = '/user/saved-listings/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'browse_by_state_and_categories' WHERE `uri` = '/browse-by-state-and-categories/' AND `application_id` = 'MobileFrontEnd';
UPDATE `site_pages_pages` SET `id` = 'sorry' WHERE `uri` = '/sorry/' AND `application_id` = 'MobileFrontEnd';
