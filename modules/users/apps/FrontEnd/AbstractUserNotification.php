<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\apps\FrontEnd;

abstract class AbstractUserNotification implements IUserNotification
{
	public function getValue($userSid)
	{
		return \App()->DB->getSingleValue("SELECT `value` FROM `users_notifications` WHERE `user_sid` = ?n AND `notification_id` = ?s", $userSid, $this->getId());
	}

	public function setValue($userSid, $value)
	{
		\App()->DB->query("DELETE FROM `users_notifications` WHERE `notification_id` = ?s AND `user_sid` = ?n", $this->getId(), $userSid);
		\App()->DB->query("INSERT INTO `users_notifications` SET `notification_id` = ?s, `user_sid` = ?n, `value` = ?n", $this->getId(), $userSid, $value);
	}
}
