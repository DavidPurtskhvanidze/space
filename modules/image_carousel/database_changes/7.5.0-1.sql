
ALTER TABLE `image_carousel_images` CHANGE `image_filename` `image_file_name`  varchar(200) DEFAULT NULL;
ALTER TABLE `image_carousel_images` ADD `image_file_size` int(11) DEFAULT NULL;
ALTER TABLE `image_carousel_images` ADD `image_content_type` varchar(30) DEFAULT NULL;
