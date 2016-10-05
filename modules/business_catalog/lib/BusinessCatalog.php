<?php
/**
 *
 *    Module: business_catalog v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: business_catalog-7.5.0-1
 *    Tag: tags/7.5.0-1@19772, 2016-06-17 13:18:58
 *
 *    This file is part of the 'business_catalog' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\business_catalog\lib;

class BusinessCatalog
{
	function bcreate_category($caption, $id)
	{
		return \App()->DB->query("INSERT INTO `business_catalog_categories` (`name`, `id`) VALUES(?s, ?s)", $caption, $id);
	}

	function bcategory_exists($category_id)
	{
		$result = \App()->DB->query("SELECT * FROM `business_catalog_categories` WHERE `id`=?s", $category_id);
		return !empty($result);
	}

	function bdelete_category($category_id)
	{
		if(!$this->bcategory_exists($category_id))
		{
			return false;
		}

		if(\App()->DB->query("DELETE FROM `business_catalog_records` WHERE `category_id` = ?s", $category_id) === false)
		{
			return false;
		}

		return (bool) \App()->DB->query("DELETE FROM `business_catalog_categories` WHERE `id` = ?s", $category_id);
	}

	function bedit_category($category_id,$caption,$new_category_id)
	{
		if (!$this->bcategory_exists($category_id))
		{
			return false;
		}
		return (bool) \App()->DB->query('UPDATE `business_catalog_categories` SET `name` = ?s, `id`= ?s WHERE `id`= ?s', $caption, $new_category_id, $category_id);
	}

	function bget_categories($sortingField = 'none', $sortingOrder = 'none')
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
		
		return \App()->DB->query("SELECT * FROM `business_catalog_categories` ORDER BY `$sortingField` $sortingOrder");
	}

	function bget_categories_www()
	{
		return \App()->DB->query('SELECT `business_catalog_categories`.`id`, `business_catalog_categories`.`name`,  count(business_catalog_records.id) as count FROM `business_catalog_categories`, `business_catalog_records` WHERE `business_catalog_records`.`category_id` = `business_catalog_categories`.`id` GROUP BY `business_catalog_categories`.`id`');
	}

	function bget_category($category_id)
	{
		$r = \App()->DB->query("SELECT * FROM `business_catalog_categories` WHERE `id` = ?s", $category_id);
		if($r === false)
		{
			return false;
		}
		return $r[0];
	}

	function bcreate_record($category_id,$name,$description,$address,$phone,$fax,$email,$url,$full)
	{
		if(!$this->bcategory_exists($category_id))
		{
			return false;
		}
		
		return \App()->DB->query("INSERT INTO `business_catalog_records` (`category_id`,`name`,`description`,`phone`,`fax`,`email`,`url`,`full`) VALUES(?s, ?s, ?s, ?s, ?s, ?s, ?s, ?s)",
			$category_id, $name, $description, $phone, $fax, $email, $url, $full);
	}

	function brecord_exists($category_id,$record_id)
	{
		$result = \App()->DB->query("SELECT * FROM `business_catalog_records` WHERE `id` =?s AND `category_id` = ?s", $record_id, $category_id);
		return !empty($result);
	}

	function bget_record($record_id)
	{
		$result = \App()->DB->query("SELECT * FROM `business_catalog_records` WHERE `id`=?n", $record_id);
		return array_pop ($result);
	}

	function bedit_record($record_id,$category_id,$name,$description,$address,$phone,$fax,$email,$url,$full)
	{
		if(!$this->bcategory_exists($category_id))
		{
			return false;
		}
		return \App()->DB->query("UPDATE `business_catalog_records` SET `category_id`=?s, `name`=?s, `description`=?s, `address`=?s, `phone`=?s, `fax`=?s, `email`=?s, `url`=?s, `full`=?s WHERE `id`=?n",
							$category_id, $name, $description, $address, $phone, $fax, $email, $url, $full, $record_id);
	}

	function bget_records($category_id, $sortingField = 'none', $sortingOrder = 'none')
	{
		static $sortingOrders = array('ASC', 'DESC');
		static $sortableFields = array('name');
		if (!in_array($sortingOrder, $sortingOrders))
		{
			$sortingOrder = 'ASC';
		}
		if (!in_array($sortingField, $sortableFields))
		{
			$sortingField = $sortableFields[0];
		}
		
		return \App()->DB->query("SELECT * FROM `business_catalog_records` WHERE `category_id`=?s ORDER BY `$sortingField` $sortingOrder", $category_id);
	}

	function bdelete_record($record_id)
	{
		return (bool) \App()->DB->query("DELETE FROM `business_catalog_records` WHERE `id` = ?s", $record_id);
	}
}

?>
