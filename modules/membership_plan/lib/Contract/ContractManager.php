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


namespace modules\membership_plan\lib\Contract;

class ContractManager implements \core\IService
{
	/**
	 * @var ContractDBManager
	 */
	private $dbManager;

	public function init()
	{
		$this->dbManager = new ContractDBManager();
	}
	public function deleteContract($contractSID)
	{
		return $this->dbManager->delete($contractSID) && \App()->ContractPackagesManager->deletePackagesByContractSID($contractSID);
    }
	function getSIDsOfContractsExpiredBeetwen($periodStart, $periodEnd)
	{
		$expiredSubscriptionContracts =	$this->dbManager->getExpiredSubscriptionContractsBeetwen($periodStart, $periodEnd);
		$expiredFeeBasedContracts =	$this->dbManager->getExpiredFeeBasedContractsBeetwen($periodStart, $periodEnd);

		$contractsSID = array();
		foreach ($expiredSubscriptionContracts as $expiredContract)
		{
			$contractsSID[] = $expiredContract['sid'];
		}
		foreach ($expiredFeeBasedContracts as $expiredContract)
		{
			$contractsSID[] = $expiredContract['sid'];
		}

		return $contractsSID;
	}
    function getContractInfoBySID($contractSID)
	{
        return $this->dbManager->getContractInfoBySID($contractSID);
    }
	function createTemplateStructureForContract($contract)
    {
		$AllPackageInfo = $contract->getListingPackagesInfo();
		$packageTemplateStructure = array();
		$packageTypes = \App()->PackageManager->getPackageTypes();
		foreach($AllPackageInfo as &$packageInfo)
		{
			$package = \App()->PackageManager->createPackageFromListingInfo($packageInfo);
			$packageDetails = array();
			$packageDetailsForDisplayExtPoint = $packageTypes[$package->getClassName()]['package_details_for_display_actions'];
			$packageDetailsForDisplayActions = new \core\ExtensionPoint($packageDetailsForDisplayExtPoint);
			foreach ($packageDetailsForDisplayActions as $packageDetailsForDisplayAction)
			{
				$packageDetailsForDisplayAction->setPackage($package);
				$packageDetails = array_merge($packageDetails, $packageDetailsForDisplayAction->perform());
			}
			
			$packageTemplateStructure[] = array(
				'name' => $package->getPropertyDisplayValue('name'),
				'description' => $package->getPropertyDisplayValue('description'),
				'packageDetails' => $packageDetails,
			);
		}

    	$structure = array
    	(
    		'packages'		=> $packageTemplateStructure,
    		'expired_date'	=> $contract->getExpiredDate(),
    		'auto_extend'	=> $contract->isAutoExtend(),
    	);

    	return array_merge($structure, $contract->getExtraInfo());
	}
	function updateContractAutoExtendFlag($contractSID, $autoExtendFlag)
	{
		return $this->dbManager->updateContractAutoExtendFlag($contractSID, $autoExtendFlag);
	}
	function getAutoExtendContractSIDsByMembershipPlanSID($membershipPlanSID)
	{
		return $this->dbManager->getAutoExtendContractSIDsByMembershipPlanSID($membershipPlanSID);
	}
	function disableAutoExtendForContractsBySIDs($contractsSIDs)
	{
		return $this->dbManager->disableAutoExtendForContractsBySIDs($contractsSIDs);
	}
	function getContractSIDsByMembershipPlanSID($membershipPlanSID)
	{
		return $this->dbManager->getContractSIDsByMembershipPlanSID($membershipPlanSID);
	}
	function getMembershipPlanSidByContractSid($contractSid)
	{
		return $this->dbManager->getMembershipPlanSidByContractSid($contractSid);
	}
	
	public function createContractByMembershipPlanSID($membershipPlanSID)
	{
		$membershipPlan = \App()->MembershipPlanManager->getMembershipPlanBySID($membershipPlanSID);
		$info = array
		(
			'membership_plan_sid'	=> $membershipPlanSID,
			'type'					=> $membershipPlan->getType(),
			'price'					=> $membershipPlan->getPrice(),
			'expired_date'			=> null,
			'serialized_extra_info'	=> null,
		);
		if (!strcasecmp($membershipPlan->getType(), "Subscription") )
		{
			$subscriptionPeriod  = $membershipPlan->getSubscriptionPeriod();
			if($subscriptionPeriod)
			{
                $info['expired_date'] = \App()->TimeService->calculateExpiredDate('Y-m-d', 'P' . $subscriptionPeriod . 'D');
			}
			$info['serialized_extra_info'] = serialize($membershipPlan->getHashedFields());
		}
		$contract = new Contract();
		$contract->buildPropertiesWithData($info);
		$contract->setPackagesInfo(\App()->PackageManager->getPackagesInfoByMembershipPlanIdGroupByClass($membershipPlanSID));

		return $contract;
	}

	/**
	 * @param $sid
	 * @return Contract|null
	 */
	public function getContractBySID($sid)
	{
        $info = $this->dbManager->getContractInfoBySID($sid);
        if (is_null($info)) return null;

        $contract = new Contract();
        $contract->setSID($sid);

		if (!strcasecmp($info['type'], 'Fee Based'))
		{
			$mp = \App()->MembershipPlanManager->getMembershipPlanBySID($info['membership_plan_sid']);

			$subscriptionPeriod = $mp->getSubscriptionPeriod();
			if($subscriptionPeriod)
			{
              $info['expired_date'] = \App()->TimeService->calculateExpiredDate('Y-m-d', 'P' . $subscriptionPeriod . 'D', $info['creation_date']);
			}
			$membershipPlanInfo = \App()->MembershipPlanManager->getMembershipPlanInfoBySID($info['membership_plan_sid']);
			$info['serialized_extra_info'] = $membershipPlanInfo['serialized_extra_info'];
		}
		$contract->buildPropertiesWithData($info);
		if (!strcasecmp($info['type'], 'Subscription'))
		{
			$packagesInfo = \App()->ContractPackagesManager->getPackagesByContractIdGroupByClass($sid);
		}
		else
		{
			$packagesInfo = \App()->PackageManager->getPackagesInfoByMembershipPlanIdGroupByClass($info['membership_plan_sid']);
		}
		$contract->setPackagesInfo($packagesInfo);

		return $contract;
	}
    function saveContract($contract)
    {
    	if ($this->dbManager->saveContract($contract))
    	{
    		if (!strcasecmp($contract->getType(), "Subscription"))
    		{
    			$packagesInfo = $contract->getPackagesInfo();
    			foreach ($packagesInfo as $class => $packages)
    			{
    				foreach ($packages as $packageInfo)
    				{
					    \App()->ContractPackagesManager->saveContractPackageInfo($contract->getSID(), $packageInfo);
					}
				}
			}
			
			return true;
		}
		
		return false;
    }
}
