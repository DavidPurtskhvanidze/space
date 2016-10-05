<?php
/**
 *
 *    Module: membership_plan v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: membership_plan-7.5.0-1
 *    Tag: tags/7.5.0-1@19798, 2016-06-17 13:20:05
 *
 *    This file is part of the 'membership_plan' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\membership_plan\lib\ListingPackage;

class ListingPackageManager implements \core\IService
{
	private $dbManager;

	public function init()
	{
		$this->dbManager = new \modules\membership_plan\lib\ListingPackage\ListingPackageDBManager();
	}

    public function getPackageSIDByListingSID($listingSID)
    {
		return $this->dbManager->getPackageSIDByListingSID($listingSID);
	}
	public function isListingSIDExist($listingSID)
	{
		$result = $this->dbManager->getPackageSIDByListingSID($listingSID);
		return !empty($result);
	}
	public function insertPackage($listingSID, $packageInfo)
	{
		$packageSID = $packageInfo['package_sid'];
		$packageInfo = serialize($packageInfo);

		return $this->dbManager->insertPackage($listingSID, $packageSID, $packageInfo);
	}
    public function updatePackage($listingSID, $packageInfo)
    {
		$packageSID = $packageInfo['package_sid'];
		$packageInfo = serialize($packageInfo);

		return $this->dbManager->updatePackage($listingSID, $packageSID, $packageInfo);
	}
	public function getPackageInfoByListingSID($listingSID)
	{
		$packageInfo = $this->dbManager->getPackageInfoByListingSID($listingSID);
		if (is_null($packageInfo))
		{
			return null;
		}
		else
		{
			$packageInfo = unserialize($packageInfo);
			if (isset($packageInfo['contract_sid']))
				$packageInfo['membership_plan_sid'] = \App()->ContractManager->getMembershipPlanSidByContractSid($packageInfo['contract_sid']);
		}
		
		return $packageInfo;
	}
    public function deleteListingPackageByListingSID($listingSID)
    {
		return $this->dbManager->deleteListingPackageByListingSID($listingSID);
    }
	public function createTemplateStructureForPackageByListingSID($listingSID)
	{
		$packageInfo = $this->getPackageInfoByListingSID($listingSID);

		$structure = array
		(
            'id'				=> $packageInfo['sid'],
			'caption'			=> $packageInfo['name'],
			'description'		=> $packageInfo['description'],
			'price'				=> $packageInfo['price'],
			'listing_lifetime'	=> $packageInfo['listing_lifetime'],
			'pictures_allowed'	=> $packageInfo['pic_limit'],
			'video_allowed'		=> $packageInfo['video_allowed'],
		);

		$extraDetails = new \core\ExtensionPoint('modules\membership_plan\IListingPackageExtraDetail');
		foreach ($extraDetails as $extraDetail)
		{
			$propertyId = $extraDetail->getId();
			$structure[$propertyId] = isset($packageInfo[$propertyId]) ? $packageInfo[$propertyId] : null;
		}

		return $structure;
	}
	public function createDisplayTemplateStructureForPackageByListingSID($listingSID)
	{
		$package = \App()->PackageManager->createPackageFromListingInfo($this->getPackageInfoByListingSID($listingSID));
		
		$packageDetails = array();
		$packageDetailsForDisplayActions = new \core\ExtensionPoint('modules\membership_plan\apps\AdminPanel\IPackageDetailsForDisplay');
		foreach ($packageDetailsForDisplayActions as $packageDetailsForDisplayAction)
		{
			$packageDetailsForDisplayAction->setPackage($package);
			$packageDetails = array_merge($packageDetails, $packageDetailsForDisplayAction->perform());
		}
		
		$packageTemplateStructure = array(
			'name' => $package->getPropertyDisplayValue('name'),
			'description' => $package->getPropertyDisplayValue('description'),
			'packageDetails' => $packageDetails,
		);
		
		return $packageTemplateStructure;
	}
    public function getListingSIDsByPackageSID($packageSID)
    {
    	if (empty($packageSID))
		{
			return null;
		}
		
        return ($packageSID === 'deleted') ? $this->dbManager->getDeletedListingSIDs() : $this->dbManager->getListingSIDsByPackageSID($packageSID);
    }

	public function getAllPackagesInfo()
	{
		$allPackagesInfo = \App()->DB->query("SELECT * FROM `membership_plan_listing_packages`");
		$allPackagesInfo = array_map(function ($packageInfo)
		{
			$packageInfo = array_merge((array)unserialize($packageInfo['package_info']), $packageInfo);
			unset($packageInfo['package_info']);
			return $packageInfo;
		}, $allPackagesInfo);

		return $allPackagesInfo;
	}
}
