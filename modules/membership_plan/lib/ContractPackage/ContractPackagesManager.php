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


namespace modules\membership_plan\lib\ContractPackage;

class ContractPackagesManager implements \core\IService
{
	/**
	 * @var ContractPackagesDBManager
	 */
	private $dbManager;
	public function init()
	{
		$this->dbManager = new \modules\membership_plan\lib\ContractPackage\ContractPackagesDBManager();
	}

	function getPackagesByContractIdGroupByClass($contractSID)
	{
		$result = $this->dbManager->getPackagesByContractSID($contractSID);

		$packagesGroupByClass = array();
		foreach ($result as $package)
		{
			$packageInfo = array_merge($package, unserialize($package['fields']));
			unset($packageInfo['fields']);
			$packagesGroupByClass[$package['class_name']][] = $packageInfo;
		}

		return $packagesGroupByClass;
	}
	function getPackageInfoBySID($package_id)
	{
		$package = $this->dbManager->getPackageInfoBySID($package_id);
		
		$package = array_merge($package, unserialize($package['fields']));
		unset($package['fields']);
		
		return $package;
	}
	function deletePackagesByContractSID($contractSID)
	{
		return $this->dbManager->deletePackagesByContractSID($contractSID);
    }

	/**
	 * @param int $contractSID
	 * @param \modules\membership_plan\lib\Package\Package $membershipPlanPackage
	 * @return bool
	 */
	public function updateContractPackageByMembershipPlanPackage($contractSID, $membershipPlanPackage)
	{
		$this->dbManager->deletePackagesByContractSIDAndPackageSID($contractSID, $membershipPlanPackage->getSID());

		return $this->dbManager->saveContractPackage(
			$contractSID,
			$membershipPlanPackage->getSID(),
			$membershipPlanPackage->getClassName(),
			serialize($membershipPlanPackage->getHashedFields())
		);
	}
	public function saveContractPackageInfo($contractSID, $packageInfo)
	{
		return $this->dbManager->saveContractPackage(
			$contractSID,
			$packageInfo['package_sid'],
			$packageInfo['class_name'],
			serialize($packageInfo)
		);
	}

	public function getAllPackagesInfo()
	{
		$packagesInfo = \App()->DB->query('SELECT * FROM `membership_plan_contract_packages`');
		$packagesInfo = array_map(function ($packageInfo)
		{
			$packageInfo = array_merge(unserialize($packageInfo['fields']), $packageInfo);
			unset($packageInfo['fields']);
			return $packageInfo;
		}, $packagesInfo);
		return $packagesInfo;
	}

	public function updateContractPackageExtraInfo($packageInfo)
	{
		return \App()->DB->query('UPDATE `membership_plan_contract_packages` SET `fields` = ?s WHERE `sid` = ?n', serialize($packageInfo), $packageInfo['sid']);
	}
}
