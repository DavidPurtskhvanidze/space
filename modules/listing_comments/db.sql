CREATE TABLE IF NOT EXISTS `listing_comments` (
	`sid` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`listing_sid` int(10) unsigned DEFAULT NULL,
	`parent_comment_sid` int(10) unsigned DEFAULT '0',
	`user_sid` int(10) unsigned DEFAULT NULL,
	`comment` text DEFAULT NULL,
	`published` tinyint(1) unsigned DEFAULT NULL,
	`posted` datetime DEFAULT NULL,
	`last_user_ip` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `site_pages_pages` (`id`, `uri`, `pass_parameters_via_uri`, `module`, `function`, `template`, `title`, `application_id`, `parameters`, `keywords`, `description`) VALUES
('comment_add', '/comment/add/', 0, 'listing_comments', 'add_listing_comment', '', 'Add Listing Comment', 'FrontEnd', 'a:0:{}', '', ''),
('comments', '/comments/', 1, 'listing_comments', 'display_comments', '', 'Listing Comments', 'FrontEnd', 'a:0:{}', '', ''),
('listing_comments', '/listing/comments/', 1, 'listing_comments', 'display_comments', '', 'Listing Comments', 'MobileFrontEnd', 'a:0:{}', '', ''),
('comment_add', '/comment/add/', 0, 'listing_comments', 'add_listing_comment', '', 'Listing Comments', 'MobileFrontEnd', 'a:0:{}', '', '');

INSERT INTO `core_settings` (`name`, `value`) VALUES
	('notify_on_comment_added', '1');

INSERT INTO `email_templates` (`id`, `subject`, `body`, `last_modified`) VALUES
('admin_add_comment_email', 'A New Comment/Reply Added to {$GLOBALS.site_url}', '<p>Dear Administrator,&nbsp; &nbsp;&nbsp;</p>\r\n\r\n<p>&nbsp;&nbsp; &nbsp;There is a new comment or reply posted on {$GLOBALS.site_url} to the listing&nbsp;<a href="{$admin_site_url}/display_listing/?listing_id={$comment.listing_sid}">{$comment.listing_sid} &quot;{$comment.listing}&quot;</a>.<br />\r\n&nbsp;&nbsp; &nbsp;Please click <a href="{$admin_site_url}/system/listing_comments/manage_comments/?action=search&listing_id[equal]={$comment.listing_sid}&parent_comment_sid[equal]=0">here</a> to read it.</p>\r\n', '2014-12-30 14:16:19'),
('admin_report_improper_comment_content', 'Improper Content of the Comment #{$comment.sid} on {$GLOBALS.site_url}', '<p>The visitor {$formData.FullName} (email {$formData.Email}) of your site {$GLOBALS.site_url} reported an improper content&nbsp;<br />\r\nof the&nbsp;<a href="{$admin_site_url}/system/listing_comments/manage_comments/?action=search&amp;listing_id[equal]={$comment.listing_sid}&amp;amp;parent_comment_sid[equal]={$comment.parent_comment_sid}">comment {$comment.sid} of the listing #{$comment.listing_sid} "{$comment.listing}"</a>:<br />\r\n{$formData.Report}</p>\r\n\r\n<p>&nbsp;</p>\r\n', '2015-04-23 14:49:44'),
('new_comment_added', 'A New Comment/Reply Added to Your Listing {$listing.sid} on the Site {$GLOBALS.front_end_url}', '<p>Dear {$user.username},<br />\r\n&nbsp;&nbsp; &nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;There is a new comment or reply posted to your listing <a href="{$GLOBALS.front_end_url}/listing/{$listing.sid}/">{$listing.sid} &quot;{$listing}&quot;</a>.<br />\r\n&nbsp;&nbsp; &nbsp;Please click <a href="{$GLOBALS.front_end_url}/comments/{$listing.sid}/#comment{$comment.sid}">here</a>&nbsp;to read it.</p>\r\n\r\n<p>&nbsp;&nbsp; {$GLOBALS.front_end_url} Administration</p>\r\n', '2014-12-30 11:22:18'),
('new_reply_posted', 'A New Reply Added to Your Comment {$comment.sid} on {$GLOBALS.front_end_url}', '<p>Dear {$user.username},&nbsp; &nbsp;&nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;There is a new reply posted to your comment <a href="{$GLOBALS.front_end_url}/comments/{$listing.sid}/#comment{$comment.sid}">{$comment.sid}&quot;</a><br />\r\n&nbsp;&nbsp; &nbsp;for the listing <a href="{$GLOBALS.front_end_url}/listing/{$listing.sid}/">{$listing.sid} &quot;{$listing}&quot;</a>.&nbsp;<br />\r\n&nbsp;&nbsp; &nbsp;Please click <a href="{$GLOBALS.front_end_url}/comments/{$listing.sid}/#comment{$reply.sid}">here</a>&nbsp;to read it.</p>\r\n', '2014-12-30 14:13:38');
