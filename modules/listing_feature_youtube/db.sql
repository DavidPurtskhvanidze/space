ALTER TABLE `classifieds_listings` ADD `feature_youtube` BOOLEAN NOT NULL DEFAULT '0';
ALTER TABLE `classifieds_listings` ADD `feature_youtube_video_id` varchar(256) DEFAULT NULL;
