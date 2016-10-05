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

class ListingPackageDBManager extends \lib\ORM\ObjectDBManager
{
    function getPackageSIDByListingSID($listingSID)
    {
    	$result = \App()->DB->query('SELECT `package_sid` FROM `membership_plan_listing_packages` WHERE `listing_sid` = ?n', $listingSID);

		return $this->extractField($result);
	}
	public function insertPackage($listingSID, $packageSID, $packageInfo)
	{
		return \App()->DB->query('INSERT INTO `membership_plan_listing_packages` (`listing_sid`, `package_sid`, `package_info`) VALUES(?n, ?n, ?s)', $listingSID,$packageSID, $packageInfo);
	}
    public function updatePackage($listingSID, $packageSID, $packageInfo)
    {
        return \App()->DB->query('UPDATE `membership_plan_listing_packages` SET `package_info` = ?s, `package_sid` = ?n WHERE `listing_sid` = ?n', $packageInfo, $packageSID, $listingSID);
	}
	public function getPackageInfoByListingSID($listingSID)
	{
		$result = \App()->DB->query('SELECT `package_info` FROM `membership_plan_listing_packages` WHERE `listing_sid` = ?n', $listingSID);

		return $this->extractField($result);
	}
    public function deleteListingPackageByListingSID($listingSID)
    {
        return \App()->DB->query('DELETE FROM `membership_plan_listing_packages` WHERE `listing_sid`=?n', $listingSID);
    }
	public function getDeletedListingSIDs()
	{
		$queryResult = \App()->DB->query('SELECT `membership_plan_listing_packages`.`listing_sid` FROM `membership_plan_listing_packages` LEFT OUTER JOIN `membership_plan_packages` ON `membership_plan_listing_packages`.`package_sid`=`membership_plan_packages`.`sid` WHERE `membership_plan_packages`.`sid` IS NULL');

		return $this->extractColumn($queryResult);
	}
	public function getListingSIDsByPackageSID($packageSID)
	{
		$queryResult = \App()->DB->query('SELECT `listing_sid` FROM `membership_plan_listing_packages` WHERE `package_sid`=?n',$packageSID);

		return $this->extractColumn($queryResult);
	}
	private function extractField($queryResult)
	{
		$queryResult = array_pop($queryResult);

		return (!is_null($queryResult)) ? array_pop($queryResult) : null;
	}
	private function extractColumn($queryResult)
	{
		foreach($queryResult as &$record)
		{
			$record = array_shift($record);
		}

		return $queryResult;
	}
}
