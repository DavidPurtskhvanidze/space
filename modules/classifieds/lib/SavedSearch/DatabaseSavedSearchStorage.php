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

class DatabaseSavedSearchStorage implements ISavedSearchStorage
{
	private $DB;
	public function setDB($db){ $this->DB = $db; }

	private $userSid;
	public function setUserSid($x){ $this->userSid = $x; }

	public function getSearches()
	{
		$saved_searches = $this->DB->query("SELECT *, sid AS id FROM `classifieds_saved_searches` WHERE user_sid = ?n", $this->userSid);
		
		foreach($saved_searches as $key => $search_info)
		{
			$saved_searches[$key]['query_string'] = http_build_query(unserialize($search_info['data'])->getRequest());
   			$saved_searches[$key]['search_results_uri'] = unserialize($search_info['data'])->getSearchResultsUri();
		}
		
		return $saved_searches;
	}

	public function saveSearch($name, $search, &$errors)
	{
		$this->DB->query('INSERT INTO `classifieds_saved_searches` (`user_sid`, `name`, `data`) VALUES (?n, ?s, ?s)', $this->userSid, $name, serialize($search));
	}

	public function deleteSearch($id)
	{
		$this->DB->query("DELETE FROM `classifieds_saved_searches` WHERE sid = ?n AND user_sid = ?n", $id, $this->userSid);
	}

	public function enbaleAutonotification($id)
	{
		$this->DB->query("UPDATE `classifieds_saved_searches` SET `auto_notify` = '1' WHERE `sid` = ?n AND `user_sid` = ?n", $id, $this->userSid);
	}

	public function disableAutonotification($id)
	{
		$this->DB->query("UPDATE `classifieds_saved_searches` SET `auto_notify` = '0' WHERE `sid` = ?n AND `user_sid` = ?n", $id, $this->userSid);
	}

	public function getSearchCount()
	{
		$result = $this->DB->getSingleValue("SELECT count(*) AS `count` FROM `classifieds_saved_searches` WHERE `user_sid` = ?n", $this->userSid);
		return (int) $result;
	}
}
