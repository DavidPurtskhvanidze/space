ALTER TABLE `publications_categories` DROP PRIMARY KEY;
ALTER TABLE `publications_categories` ADD `sid` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST;
ALTER TABLE `publications_categories` CHANGE `id` `id` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `publications_articles` CHANGE `id` `sid` INT( 10 ) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `publications_articles` ADD `category_sid` INT UNSIGNED NOT NULL AFTER `category_id`;
ALTER TABLE `publications_articles` ADD `date` DATETIME NOT NULL;

UPDATE publications_articles, publications_categories
	SET publications_articles.category_sid = publications_categories.sid
	WHERE publications_articles.category_id = publications_categories.id;

UPDATE publications_articles SET `date` = FROM_UNIXTIME(`timestamp`);
