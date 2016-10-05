<?php
/**
 *
 *    Module: listing_option_reactivation v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_option_reactivation-7.5.0-1
 *    Tag: tags/7.5.0-1@19794, 2016-06-17 13:19:54
 *
 *    This file is part of the 'listing_option_reactivation' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_option_reactivation\lib;

class ListingReactivationManager extends \lib\ORM\ObjectDBManager implements \core\IService
{
	private $tableName = 'listing_option_reactivation_reactivations';
	
	public function isListingReactivationExist($listingSid)
	{
		$result = \App()->DB->query("SELECT 1 FROM `" . $this->tableName . "` WHERE `listing_sid` = ?n", $listingSid);
		return !empty($result);
	}

    public function createListingReactivation($info = array())
    {
		$object = new ListingReactivation();
		$object->setDetails($this->createListingReactivationDetails());
		$object->incorporateData($info);
		if (isset($info['sid']))
        {
            $object->setSid($info['sid']);
        }
		return $object;
	}		
	
	private function createListingReactivationDetails()
	{
		$details = new ListingReactivationDetails();
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
        $details->setDetailsInfo(ListingReactivationDetails::$system_details);
		$details->buildProperties();
		return $details;
	}

	public function getListingReactivationByListingSid($listingSid)
	{
		$result = \App()->DB->query("SELECT * FROM `{$this->tableName}` WHERE `listing_sid` = ?n", $listingSid);
		if (isset($result[0]))
		{
			return $this->createListingReactivation($result[0]);
		}
		else
		{
			return false;
		}
	}
	
	public function getActiveReactivations()
	{
		$reactivations = array();
		$result = \App()->DB->query("SELECT * FROM `{$this->tableName}` WHERE `activated`");
		if ($result)
		{
			foreach ($result as $recordInfo)
			{
				$reactivations[] = $this->createListingReactivation($recordInfo);
			}
		}
		
		return $reactivations;
	}
	
	public function activateListingReactivationByListingSid($listingSid)
	{
		$object = $this->getListingReactivationByListingSid($listingSid);
		$object->setPropertyValue('activated', true);
		$this->saveObject($object);
	}
	
	public function deleteListingReactivationBySid($sid)
	{
		parent::deleteObject($this->tableName, $sid);
	}

	public function deleteListingReactivationByListingSid($listingSid)
	{
		\App()->DB->query("DELETE FROM `{$this->tableName}` WHERE `listing_sid` = ?n", $listingSid);
	}

	public function deleteListingReactivationByUserSid($userSid)
	{
		\App()->DB->query("DELETE FROM `{$this->tableName}` WHERE `user_sid` = ?n", $userSid);
	}
	
	public function updatePackgeInfoByPackageSid($packageSid, $packageInfo)
	{
		\App()->DB->query("UPDATE `{$this->tableName}` SET `package_info` = ?s WHERE `package_sid` = ?n", serialize($packageInfo), $packageSid);
	}
}
