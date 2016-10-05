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


namespace modules\classifieds\lib\ListingField;

class ListingFieldDBManager extends \lib\ORM\ObjectDBManager
{
	function getCommonListingFieldsInfo()
	{
		return $this->getListingFieldsInfoByCategory(0);
	}

	/**
	 * @param ListingField $listing_field
	 * @return bool
	 */
	public function saveListingField($listing_field)
	{
		parent::saveObject($listing_field);
		return \App()->DB->queryNoReplace("UPDATE `classifieds_listing_fields` SET `category_sid` = {$listing_field->getCategorySID()} WHERE `sid` = {$listing_field->getSID()}");

	}

	function getListingFieldInfoBySID($listing_field_sid)
	{
		$listing_field_info = parent::getObjectInfoCached("classifieds_listing_fields", $listing_field_sid);
		$this->setComplexFields($listing_field_info);
		return $listing_field_info;
	}
	
	function setComplexFields(&$listing_field_info)
	{
		if ($listing_field_info['type'] == 'list')
		{
			$listing_field_info['list_values'] = $this->getListValuesBySID($listing_field_info['sid']);
			
		} 
		elseif($listing_field_info['type'] == 'multilist')
		{
			$listing_field_info['list_values'] = $this->getMultiListValuesBySID($listing_field_info['sid']);
		}
		elseif ($listing_field_info['type'] == 'tree')
		{
			$listing_field_info['tree_values'] = $this->getTreeValuesBySID($listing_field_info['sid']);
			$listing_field_info['tree_depth'] = $this->getTreeDepthBySID($listing_field_info['sid']);
		}
	}

	function getTreeValuesBySID($field_sid) {
		$cacheId = "getTreeValuesBySID_" . $field_sid;
		if(!\App()->MemoryCache->exists($cacheId))
		{
			$field_values = \App()->ListingFieldTreeManager->getTreeValuesBySID($field_sid);
			\App()->MemoryCache->set($cacheId, $field_values);
		}
		else
		{
			$field_values = \App()->MemoryCache->get($cacheId);
		}

		return $field_values;
		
	}
	
	function &getTreeDepthBySID($field_sid) {
		$cacheId = "getTreeDepthBySID_" . $field_sid;
		if(!\App()->MemoryCache->exists($cacheId))
		{
			$field_values = \App()->ListingFieldTreeManager->getTreeDepthBySID($field_sid);
			\App()->MemoryCache->set($cacheId, $field_values);
		}
		else
		{
			$field_values = \App()->MemoryCache->get($cacheId);
		}

		return $field_values;
	}

	function &getListValuesBySID($listing_field_sid) {
		$cacheId = "getListValuesBySID_" . $listing_field_sid;
		if(!\App()->MemoryCache->exists($cacheId))
		{
			$ListingFieldListItemManager = new \modules\classifieds\lib\ListingField\ListingFieldListItemManager();
			$values = $ListingFieldListItemManager->getHashedListItemsByFieldSID($listing_field_sid);
			$field_values = array();
			foreach ($values as $key => $value)
			{
				$field_values[] = array('id' => $key, 'caption' => $value);
			}
			\App()->MemoryCache->set($cacheId, $field_values);
		}
		else
		{
			$field_values = \App()->MemoryCache->get($cacheId);
		}

		return $field_values;
		
	}

	function &getMultiListValuesBySID($listing_field_sid) {
		$cacheId = "getListValuesBySID_" . $listing_field_sid;
		if(!\App()->MemoryCache->exists($cacheId))
		{
			$ListingFieldListItemManager = new \modules\classifieds\lib\ListingField\ListingFieldListItemManager();
			$values = $ListingFieldListItemManager->getHashedMultiListItemsByFieldSID($listing_field_sid);
			$field_values = array();
			foreach ($values as $key => $item)
			{
				$field_values[] = array(
					'id' => $key, 
					'caption' => $item['value'], 
					'rank' => $item['rank'],
					'checked' => false,);
			}
			\App()->MemoryCache->set($cacheId, $field_values);
		}
		else
		{
			$field_values = \App()->MemoryCache->get($cacheId);
		}

		return $field_values;
		
	}

	function getListingFieldInfoByID($listing_field_id)
	{
		$sid = \App()->DB->query('SELECT `sid` FROM `classifieds_listing_fields` WHERE `id` = ?s', $listing_field_id);
		if (empty($sid)) return null;
		$listing_field_sid = $sid[0]['sid'];
		return parent::getObjectInfoCached("classifieds_listing_fields", $listing_field_sid);
	}

	function deleteListingFieldBySID($listing_field_sid)
	{
		$listing_field_info = $this->getListingFieldInfoBySID($listing_field_sid);
		if (!strcasecmp("list", $listing_field_info['type']))
		{
			\App()->DB->query("DELETE FROM `classifieds_listing_field_list` WHERE `field_sid` = ?n", $listing_field_sid);
		}
		elseif (!strcasecmp("multilist", $listing_field_info['type']))
		{
			\App()->DB->query("DELETE FROM `classifieds_listing_field_list` WHERE `field_sid` = ?n", $listing_field_sid);
		}
		elseif (!strcasecmp("tree", $listing_field_info['type']))
		{
			\App()->ListingFieldTreeManager->deleteTreeEntriesForFieldWithSid($listing_field_sid);
		}
		elseif (!strcasecmp("rating", $listing_field_info['type']))
		{
			\App()->DB->query("DELETE FROM `classifieds_rating` WHERE `field_sid` = ?n", $listing_field_sid);
		}
		return parent::deleteObjectInfoFromDB("classifieds_listing_fields", $listing_field_sid);
	}

	function getListingFieldsInfoByCategory($category_sid)
	{
		$all_ids = \App()->CategoryManager->getCategoryParentTreeBySID($category_sid);
		if (empty($all_ids)) return array();
		$listing_fields_info = $this->getListingFieldsInfoForCategorySID($all_ids, $category_sid);
		return $listing_fields_info;
	}
	
	private function getListingFieldsInfoForCategorySID($category_sids, $categorySidForOrdering)
	{
		$request['category_sid']['in'] = $category_sids;
		$sids = \App()->ListingFieldManager->getListingFieldSidsByRequest($request, $categorySidForOrdering);
		$listing_fields_info = array_map(array($this, 'getListingFieldInfoBySID'), $sids);
		return $listing_fields_info;
	}

    function deleteFieldProperties($field_id, $category_sid) {
        if($category_sid)
            return \App()->DB->query("DELETE FROM `listings_properties` WHERE `id`=?s AND `object_sid` IN (SELECT sid FROM `classifieds_listings` WHERE `category_sid`=?n)", $field_id, $category_sid);
        else
            return \App()->DB->query("DELETE FROM `listings_properties` WHERE `id`=?s", $field_id);
    }
    
    
    public function deleteColumnFromListingTable($columnName)
    {
    	return \App()->DB->query("ALTER TABLE `classifieds_listings` DROP COLUMN `$columnName`");
    }

}
