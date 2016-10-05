ALTER TABLE `publications_articles` ADD `picture` int(11) DEFAULT NULL;
ALTER TABLE `publications_articles` ADD `picture_url` varchar(255) DEFAULT NULL;

INSERT INTO `core_settings` (`name`, `value`) VALUES
  ('article_picture_width', '150'),
  ('article_picture_height', '150'),
  ('article_picture_storage_method', 'file_system');
