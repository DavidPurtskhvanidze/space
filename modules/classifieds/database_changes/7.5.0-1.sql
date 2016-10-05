ALTER TABLE `classifieds_categories` ADD `active_listing_number` INT DEFAULT 0;
ALTER TABLE `classifieds_categories` ADD `listing_number` INT DEFAULT 0;

ALTER TABLE  `classifieds_listing_fields` CHANGE  `category_sid`  `category_sid` INT( 10 ) UNSIGNED NULL DEFAULT  '0';

ALTER TABLE `classifieds_listings` CHANGE  `ListingRating`  `ListingRating` VARCHAR( 150 ) NULL DEFAULT '0';

UPDATE `classifieds_categories`
SET `listing_number` = (SELECT count(*)
                        FROM `classifieds_listings`
                        WHERE `classifieds_categories`.`sid` = `classifieds_listings`.`category_sid`);

UPDATE `classifieds_categories`
SET `active_listing_number` = (SELECT count(*)
                               FROM `classifieds_listings`
                               WHERE `classifieds_categories`.`sid` = `classifieds_listings`.`category_sid`
                                     AND `classifieds_listings`.`active` = 1);

UPDATE `classifieds_listings`
SET `ListingRating` = CONCAT(`ListingRating`, '|', (SELECT count(*)
                                                    FROM `classifieds_rating`
                                                    WHERE `classifieds_rating`.`object_type` = 'listing' AND
                                                          `classifieds_rating`.`object_sid` =
                                                          `classifieds_listings`.sid
)
);

INSERT INTO `email_templates` (`id`, `subject`, `body`, `last_modified`) VALUES
('admin_edit_listing_pending_email', '{$GLOBALS.site_url}: Listing Has Just Been Modified', '<p>Dear Admin,&nbsp; &nbsp;&nbsp;<br />\r\nListing {$listing.id}&nbsp;was modified on your website at {$GLOBALS.site_url}.</p>\r\n\r\n<p>Here are the details:</p>\r\n\r\n<p>&nbsp; &nbsp;&nbsp;Listing ID: {$listing.id} <a href="{$GLOBALS.site_url}/admin/display_listing/?listing_id={$listing.id}">"{$listing}"</a><br />\r\n&nbsp;&nbsp; &nbsp;Modified by: &nbsp;{$user.username}<br />\r\n&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;{$GLOBALS.site_url} Administrator</p>\r\n', '2016-05-31 18:37:04');
