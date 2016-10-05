CREATE TABLE IF NOT EXISTS `email_templates` (
  `id` varchar(250) NOT NULL,
  `subject` varchar(1000) NOT NULL,
  `body` text NOT NULL,
  `last_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `authentication_failures_blocklist` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(32) NOT NULL,
  `time` timestamp NOT NULL,
  `email_sended` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `authentication_failures` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL,
  `time` timestamp NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `core_settings` (`name`, `value`) VALUES
('lf_limit', '3'),
('lf_time', '3'),
('lf_time_block', '5'),
('task_scheduler_last_start_date', '2007-09-30 15:37:07'),
('task_scheduler_last_end_date', '2007-09-30 15:37:27');

INSERT INTO `email_templates` (`id`, `subject`, `body`, `last_modified`) VALUES
('password_guessing', 'Unsuccessful Admin Login Attempt', '<p>Dear Administrator,</p>\r\n\r\n<p><span style="color:rgb(38, 38, 38); font-family:arial,sans-serif">The system registered {$limit}&nbsp;unsuccessful admin login attempts with the username {$username}, coming from the following IP address: {$ip}.&nbsp;Timestamp - {$Timestamp}</span><br />\r\n<br />\r\n<span style="color:rgb(38, 38, 38); font-family:arial,sans-serif">The login attempts exceeded the max number of failed attempts, and the system suspended the account. It will be automatically unsuspended after the time specified in the System Settings.</span><br />\r\n<br />\r\n<span style="color:rgb(38, 38, 38); font-family:arial,sans-serif">If it weren''t you, please make sure your username/password combination meets prudent security requirements to make sure your system remains safe and secure.</span><br />\r\n<span style="color:rgb(38, 38, 38); font-family:arial,sans-serif">Automated response</span></p>\r\n', '2015-02-24 15:35:14'),
('password_change_email', '{$GLOBALS.site_url}: Request to Change Admin Password', '<p>Dear {$adminInfo.username},<br />\r\n&nbsp;&nbsp; &nbsp;Someone, either you or someone else, submitted a request to change your admin password. If you did not place the request yourself, simply disregard this message. If you submitted the request, please go ahead and change your password by following the link below:<br />\r\n&nbsp;&nbsp; &nbsp;<a href="{$GLOBALS.site_url}/?action=change_password&amp;username={$adminInfo.username}&amp;verification_key={$adminInfo.verification_key}">Change your password</a></p>\r\n\r\n<p>Best regards,<br />\r\n{$GLOBALS.site_url}</p>\r\n', '2014-12-30 17:02:35');
