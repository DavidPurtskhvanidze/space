INSERT INTO `classifieds_feature_display_rotator`(`listing_sid`, `feature_type`, `order`)
	SELECT `sid`, 'Featured', UNIX_TIMESTAMP(`featured_last_showed`)
	FROM `classifieds_listings`
	WHERE `feature_featured` = 1;
