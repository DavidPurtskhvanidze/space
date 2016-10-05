<?php
/**
 *
 *    Module: static_content v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: static_content-7.5.0-1
 *    Tag: tags/7.5.0-1@19836, 2016-06-17 13:22:00
 *
 *    This file is part of the 'static_content' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\static_content\lib;

class StaticContent 
{
	function getStaticContents($sortingField = 'none', $sortingOrder = 'none')
	{
		static $sortingOrders = array('ASC', 'DESC');
		static $sortableFields = array('id', 'name');
		if (!in_array($sortingOrder, $sortingOrders))
		{
			$sortingOrder = 'ASC';
		}
		if (!in_array($sortingField, $sortableFields))
		{
			$sortingField = $sortableFields[0];
		}
		
		$res = \App()->DB->query("SELECT * FROM `static_content_pages` ORDER BY `$sortingField` $sortingOrder");
		if (!$res)
		{
			return false;
		}
	
		$pages = array ();
		foreach($res as $row)
		{
			$pages[$row['id']] = $row;
		}
		return $pages;
	}
	function getAllStaticContentIdAndName()
	{
		$res = \App()->DB->query("SELECT `id`, `name` FROM `static_content_pages`");
		if (!$res)
		{
			return false;
		}
	
		$pages = array ();
		foreach($res as $row)
		{
			$pages[$row['id']] = $row['name'];
		}
		return $pages;
	}
	/**
	 * getting information about static page
	 *
	 * Function gets information about static page and retunrs it as array
	 *
	 * @param integer $page_id ID of page
	 * @return mixed it can be array (information about page) or bool (only 'false' if operation has fallen)
	 */
	function getStaticContent($pageID)
	{
		$res = \App()->DB->query("SELECT * FROM `static_content_pages` WHERE `id` = ?s", $pageID);
		if (!$res)
		{
			return false;
		}
		return $res[0];
	}
	/**
	 * adding new  page
	 *
	 * Function creates static pages
	 *
	 * @param string $name name of page
	 * @param string $url URL of page
	 * @return bool 'true' if operation succeeded or 'false' otherwise
	 */
	function addStaticContent($contentInfo)
	{
		$name = $contentInfo['name'];
		$id = $contentInfo['id'];
		if (empty($name))
		{
			return false;
		}
		return \App()->DB->query("INSERT INTO `static_content_pages` (`name`, `id`) VALUES (?s, ?s)", $name, $id);
	}
	
	/**
	 * deleting static page
	 *
	 * Function removes static page by ID of it
	 *
	 * @param integer $page_id ID of page
	 * @return bool 'true' if operation succeeded or 'false' otherwise
	 */
	function deleteStaticContent($pageID)
	{
		return \App()->DB->query("DELETE FROM `static_content_pages` WHERE `id` = ?s", $pageID);
	}
	
	/**
	 * changing information about static page
	 *
	 * Function changes information about static page by ID of it
	 *
	 * @param integer $page_id ID of page
	 * @param string $name name of page
	 * @param string $url URL of page
	 * @return bool 'true' if operation succeeded or 'false' otherwise
	 */
	function changeStaticContent($contentInfo, $newPageID)
	{
		$pageID = $contentInfo['id'];
		$name = $contentInfo['name'];
		$content = $contentInfo['content'];
		return \App()->DB->query("UPDATE `static_content_pages` SET `name`=?s, `id`=?s, `content`=?s WHERE `id`=?s", $name, $newPageID, $content, $pageID);
	}
}
