CREATE TABLE IF NOT EXISTS `poll_answers` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL DEFAULT '',
	`counter` int(10) unsigned NOT NULL DEFAULT '0',
	`question_id` int(10) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `poll_questions` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL DEFAULT '',
	`activity` tinyint(3) unsigned NOT NULL DEFAULT '0',
	`display` tinyint(3) unsigned NOT NULL DEFAULT '0',
	`comment` text NULL DEFAULT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
	('polls', '/polls/', NULL, 'poll', 'polls_admin', '', '', 'AdminPanel', '', '', ''),
	('polls_result', '/polls/result/', NULL, 'poll', 'polls_result_admin', '', '', 'AdminPanel', '', '', '');
