<?php
/**
 *
 *    Module: third_party_login v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: third_party_login-7.3.0-1
 *    Tag: tags/7.3.0-1@18640, 2015-08-24 13:43:11
 *
 *    This file is part of the 'third_party_login' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\third_party_login\lib;

class ThirdPartyAuthDBManager extends \lib\ORM\ObjectDBManager
{
	function getUserInfoByIdentity($identity)
	{
		$user_sid = \App()->DB->getSingleValue("SELECT sid FROM `users_users` WHERE third_party_id = ?s", $identity);

		if (empty($user_sid))
		{
			return null;
		}
		else
		{
			return parent::getObjectInfo("users_users", $user_sid);
		}
	}
}
