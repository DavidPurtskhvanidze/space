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


namespace modules\classifieds\lib\Category;

class CategoryDBManager extends \lib\ORM\ObjectDBManager {

	private $categorySIDByIdCache = array();

	function getAllCategoriesInfo() {

		return parent::getObjectsInfoByType("classifieds_categories");

	}

	function saveCategory($category)
	{
		parent::saveObject($category);
		\App()->DB->query("UPDATE `classifieds_categories` SET `last_modified` = NOW() WHERE `sid` =?n", $category->getSID());
		$order = CategoryDBManager::getOrderBySID($category->getSID());

		if (empty($order))
		{
			CategoryDBManager::setMaxOrder($category->getSID(), $category->getPropertyValue('parent'));
		}
	}
	
	function getOrderBySID($category_sid)
	{
		$order = \App()->DB->getSingleValue("SELECT `order` FROM `classifieds_categories` WHERE `sid` = ?n", $category_sid);
		
		if (!empty($order))
		{
			return $order;
		}
		else
		{
			return null;
		}
	}
	
	function setMaxOrder($category_sid, $parent_sid)
	{
		$max_order = \App()->DB->getSingleValue("SELECT MAX(`order`) as `max_order` FROM `classifieds_categories` WHERE `parent` = ?n", $parent_sid);

		\App()->DB->query("UPDATE `classifieds_categories` SET `order` = ?n WHERE `sid` = ?n", $max_order + 1, $category_sid);
	}

	function getInfoBySID($category_sid) {
		return parent::getObjectInfoCached("classifieds_categories", $category_sid);

	}

	function deleteCategoryBySID($category_sid) {

		return parent::deleteObjectInfoFromDB("classifieds_categories", $category_sid);

	}

	function getCategorySIDByID($category_id)
	{
		if (isset($this->categorySIDByIdCache[$category_id])) return $this->categorySIDByIdCache[$category_id];
		$sid = \App()->DB->getSingleValue("SELECT sid FROM `classifieds_categories` WHERE id = ?s", $category_id);
		$this->categorySIDByIdCache[$category_id] = is_null($sid) ? 0 : $sid;
		return $this->categorySIDByIdCache[$category_id];
	}

	function getCategoryIDBySID($category_sid) {

		$id = \App()->DB->getSingleValue("SELECT id FROM `classifieds_categories` WHERE sid = ?s", $category_sid);

		if (empty($id)) {

			return null;

		} else {

			return $id;

		}

	}

		/* public static */
	function getCategoryParentTreeBySID($category_sid)
	{
		$rows = \App()->DB->query("SELECT `sid`, `parent` FROM `classifieds_categories`");
		$tree = array();
		foreach($rows as $row) $parent[$row['sid']] = $row['parent'];
		for ($id = $category_sid; !is_null($id);  $id = $parent[$id]) array_push ($tree, $id);
		return $tree;
	}



}

?>
