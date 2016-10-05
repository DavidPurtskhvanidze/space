<?php
/**
 *
 *    Module: basket v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: basket-7.5.0-1
 *    Tag: tags/7.5.0-1@19771, 2016-06-17 13:18:56
 *
 *    This file is part of the 'basket' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\basket\lib\BasketContainer;

class BasketContainerItemManager extends \lib\ORM\ObjectManager implements \core\IService
{
	private $tableName = 'basket_container_items';
    
    public function createItem($info = array())
    {
		$item = new BasketContainerItem();
		$item->setDetails($this->createItemDetails());
		$item->incorporateData($info);
		if (isset($info['sid']))
        {
            $item->setSid($info['sid']);
        }
		return $item;
	}		
	
	private function createItemDetails()
	{
		$details = new BasketContainerItemDetails();
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
        $details->setDetailsInfo(BasketContainerItemDetails::$system_details);
		$details->buildProperties();
		return $details;
	}
	
	public function getItemsByListingSid($listingSid)
	{
		$itemInfoSet = \App()->DB->query("SELECT * FROM `{$this->tableName}` WHERE `listing_sid` = ?n", $listingSid);
		$result = array();
		foreach($itemInfoSet as $itemInfo)
		{
			$result[($itemInfo['sid'])] = $this->createItem($itemInfo);
		}
		
		return $result;
	}
	
	public function addItems($listingSid, $optionIds)
	{
		foreach ($optionIds as $optionId)
		{
			$item = $this->createItem(
				array(
					'listing_sid' => $listingSid,
					'option_id' => $optionId
				)
			);
			$this->saveObject($item);
		}
	}

	public function deleteItemBySid($sid)
	{
		\App()->DB->query("DELETE FROM `{$this->tableName}` WHERE (`sid` = ?n)", $sid);
	}

	public function deleteItemByListingSid($listingSid)
	{
		\App()->DB->query("DELETE FROM `{$this->tableName}` WHERE `listing_sid` = ?n", $listingSid);
	}

	public function getOptionIdsByListingSid($listingSid)
	{
		$optionsInfo = \App()->DB->query("SELECT `option_id` FROM `{$this->tableName}` WHERE `listing_sid` = ?n", $listingSid);
		$optionIds = array_map(function($info)
		{
			return $info['option_id'];
		}, $optionsInfo);
		return $optionIds;
	}
}
