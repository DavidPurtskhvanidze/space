INSERT INTO `image_carousel_images` (`sid`, `caption`, `url`, `image_file_name`, `display_order`, `disabled`) VALUES
(7, 'sample slider 1', '', '1-Sports-balls-concept.jpg', 4, 0),
(8, 'sample slider 2', '', '2-Group-of-six-guitars.jpg', 5, 0),
(9, 'sample slider 3', '', '3-African-figurines-holding-drums.jpg', 6, 0),
(10, 'sample slider 3', '', '4-Wooden-elephants.jpg', 7, 0);

UPDATE `core_settings` SET `value` = '850' WHERE `name` = 'image_carousel_width';
UPDATE `core_settings` SET `value` = '255' WHERE `name` = 'image_carousel_height';
