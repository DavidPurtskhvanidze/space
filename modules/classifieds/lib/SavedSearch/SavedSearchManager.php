<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib\SavedSearch;

class SavedSearchManager implements \core\IService
{
	private static $cookieName = "SAVED_SEARCHES";

	public function getSavedSearchStorage()
	{
		$storage = null;
		if (\App()->UserManager->isUserLoggedIn())
		{
			$storage = new DatabaseSavedSearchStorage();
			$storage->setDB(\App()->DB);
			$storage->setUserSid(\App()->UserManager->getCurrentUserSID());
		}
		else
		{
			$storage = new CookieSavedSearchStorage();
			$storage->setCookieName(self::$cookieName);
			$storage->setCookieService(\App()->Cookie);
		}
		return $storage;
	}

	public function getAutoNotifySavedSearches()
	{
		return \App()->DB->query("SELECT *, `sid` AS id FROM `classifieds_saved_searches` WHERE `auto_notify` = 1");
	}

	public function deleteUserSearchesFromDB($user_sid)
	{
		\App()->DB->query("DELETE FROM `classifieds_saved_searches` WHERE `user_sid` = ?n", $user_sid);
	}
}
