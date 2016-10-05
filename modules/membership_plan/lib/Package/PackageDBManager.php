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


namespace modules\membership_plan\lib\Package;

class PackageDBManager
{
	private $packagesTableName = 'membership_plan_packages';
	private $listingPackagesTableName = 'membership_plan_listing_packages';
	private $listingsCountCache = array();

	public function savePackage($package)
	{
		$objectSid = $package->getSID();
		$fieldsData = serialize($package->getHashedFields());

		if (is_null($objectSid))
		{
			$objectSid = \App()->DB->query(
				'INSERT INTO `' . $this->packagesTableName . '`(`fields`, `class_name`, `membership_plan_sid`) VALUES(?s, ?s, ?n)',
				$fieldsData, $package->getClassName(), $package->getMembershipPlanSID()
			);
			$package->setSID($objectSid);
		}
		else
		{
			\App()->DB->query("UPDATE `membership_plan_packages` SET `fields`=?s WHERE `sid`=?n", $fieldsData, $objectSid);
		}
	}
	public function getPackageInfoBySID($packageSID)
	{
		$result = \App()->DB->query("SELECT *, `sid` AS `package_sid` FROM `" . $this->packagesTableName . "` WHERE `sid` = ?n", $packageSID);

		$record = null;
		if (!empty($result))
		{
			$record = array_pop($result);
			$record = array_merge($record, unserialize($record['fields']));
			unset($record['fields']);
		}

		return $record;
	}
	public function getPackagesInfoByMembershipPlanSID($membershipPlanSID)
	{
		$result = \App()->DB->query("SELECT *, `sid` as `package_sid` FROM `" . $this->packagesTableName . "` WHERE `membership_plan_sid` = ?n ORDER BY `order`", $membershipPlanSID);
		foreach($result as &$record)
		{
			$record['fields'] = unserialize($record['fields']);
		}

		return $result;
	}
	public function getListingQuantityByPackageSID($packageSID)
	{
		if (isset($this->listingsCountCache[$packageSID])) return $packageSID;
		$result = \App()->DB->getSingleValue("SELECT COUNT(*) FROM `" . $this->listingPackagesTableName . "` WHERE `package_sid`=?n", $packageSID);
		$this->listingsCountCache[$packageSID] = $result;
		return $result;
	}
	public function deletePackagesByMembershipPlanSID($membershipPlanSID)
	{
		return \App()->DB->query("DELETE FROM `membership_plan_packages` WHERE `membership_plan_sid`=?n", $membershipPlanSID);
	}
	public function deletePackageBySID($sid)
	{
		return \App()->DB->query('DELETE FROM `membership_plan_packages` WHERE `sid`=?n', $sid);
	}
}
