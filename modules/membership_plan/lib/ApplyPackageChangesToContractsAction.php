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


namespace modules\membership_plan\lib;

class ApplyPackageChangesToContractsAction
{
	var $package;
	var $contractManager;
	var $contractPackagesManager;
	
	function setPackage($package)
	{
		$this->package = $package;
	}
	function setContractManager($contractManager)
	{
		$this->contractManager = $contractManager;
	}
	function setContractPackagesManager($contractPackagesManager)
	{
		$this->contractPackagesManager = $contractPackagesManager;
	}
	function perform()
	{
		$membershipPlanSID = $this->package->getMembershipPlanSID();
		$contractsSIDs = $this->contractManager->getContractSIDsByMembershipPlanSID($membershipPlanSID);
		foreach ($contractsSIDs as $contractSID)
		{
			$this->contractPackagesManager->updateContractPackageByMembershipPlanPackage($contractSID, $this->package);
		}
	}
}
