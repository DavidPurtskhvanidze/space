CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` varchar(250) NOT NULL,
  `subject` varchar(1000) NOT NULL,
  `body` text NOT NULL,
  `last_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `email_templates` (`id`, `subject`, `body`, `last_modified`) VALUES
('password_change_email', '{$GLOBALS.site_url}: Request to Change Admin Password', '<p>Dear {$adminInfo.username},<br />\r\n&nbsp;&nbsp; &nbsp;Someone, either you or someone else, submitted a request to change your admin password. If you did not place the request yourself, simply disregard this message. If you submitted the request, please go ahead and change your password by following the link below:<br />\r\n&nbsp;&nbsp; &nbsp;<a href="{$GLOBALS.site_url}/?action=change_password&amp;username={$adminInfo.username}&amp;verification_key={$adminInfo.verification_key}">Change your password</a></p>\r\n\r\n<p>Best regards,<br />\r\n{$GLOBALS.site_url}</p>\r\n', '2014-12-30 17:02:35');
