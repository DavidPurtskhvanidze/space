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

class ContractPackagesDBManager extends \lib\ORM\ObjectDBManager
{
	public function getPackagesByContractSID($contractSID)
	{
		return \App()->DB->query('SELECT * FROM `membership_plan_contract_packages` WHERE `contract_sid` = ?n', $contractSID);
	}
	public function getPackageInfoBySID($packageSID)
	{
		$package = \App()->DB->query("SELECT * FROM `membership_plan_contract_packages` WHERE `sid` = ?n", $packageSID);
		
		return array_pop($package);
	}
	public function deletePackagesByContractSID($contractSID)
	{
        return \App()->DB->query("DELETE FROM `membership_plan_contract_packages` WHERE `contract_sid`=?n", $contractSID);
    }
	public function deletePackagesByContractSIDAndPackageSID($contractSID, $packageSID)
	{
        return \App()->DB->query('DELETE FROM `membership_plan_contract_packages` WHERE `contract_sid` = ?n AND `package_sid` = ?n', $contractSID, $packageSID);
    }
	public function saveContractPackage($contractSID, $packageSID, $packageClassName, $packageFields)
	{
		return \App()->DB->query(
			'INSERT INTO `membership_plan_contract_packages`(`class_name`, `contract_sid`, `package_sid`, `fields`) VALUES (?s, ?n, ?n, ?s)',
			$packageClassName, $contractSID, $packageSID, $packageFields
		);
	}
}
