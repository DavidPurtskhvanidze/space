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


namespace modules\membership_plan\lib\MembershipPlan;

class MembershipPlanManager implements \core\IService
{
	/**
	 * @var MembershipPlanDBManager
	 */
	private $dbManager;
	public function init()
	{
		$this->dbManager = new \modules\membership_plan\lib\MembershipPlan\MembershipPlanDBManager();
	}
	public function createMembershipPlan($membershipPlanData)
	{
		$membershipPlan = new MembershipPlan();
		$membershipPlan->setDetails($this->createMembershipPlanDetails());
		$membershipPlan->addSerializedExtraInfoProperty();
		$membershipPlan->incorporateData($membershipPlanData);

		$membershipPlanData = array_merge($membershipPlan->getPropertyValue('serialized_extra_info'), $membershipPlanData);
		$membershipPlan->deleteSerializedExtraInfoProperty();
		$membershipPlan->incorporateData($membershipPlanData);

		return $membershipPlan;
	}
	private function createMembershipPlanDetails()
	{
		$details = new MembershipPlanDetails();
		$details->setDetailsInfo($this->getDetails());
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildProperties();

		return $details;
	}
	public function getDetails()
	{
		return MembershipPlanDetails::getSystemDetailsInfo();
	}

	/**
	 * @param MembershipPlan $membershipPlan
	 */
	public function saveMembershipPlan($membershipPlan)
	{
		$membershipPlan->addSerializedExtraInfoProperty($membershipPlan->getHashedFields());
		$this->dbManager->saveMembershipPlan($membershipPlan);
	}
	public function getMembershipPlanBySID($membershipPlanSID)
	{
		$membershipPlanInfo = $this->dbManager->getMembershipPlanInfoBySID($membershipPlanSID);
		if (empty($membershipPlanInfo))
		{
			throw new MembershipPlanException('RECORD_NOT_FOUND');
		}

		$membershipPlan = $this->createMembershipPlan($membershipPlanInfo);
		$membershipPlan->setSID($membershipPlanSID);

		return $membershipPlan;
	}
	public function getMembershipPlanWithPackagesBySID($membershipPlanSID)
	{
		$membershipPlan = $this->getMembershipPlanBySID($membershipPlanSID);
		$membershipPlan->addPackagesProperty(
			\App()->PackageManager->getPackagesByMembershipPlanSID($membershipPlan->getSID())
		);

		return $membershipPlan;
	}
	public function getMembershipPlanForEditingBySID($membershipPlanSID)
	{
		$membershipPlan = $this->getMembershipPlanBySID($membershipPlanSID);
		$membershipPlan->getProperty('type')->save_into_db = false;

		return $membershipPlan;
	}
	private function createMembershipPlanForm($membershipPlan)
	{
		return \App()->ObjectMother->createForm($membershipPlan);
	}
	public function getCreatingFormForMembershipPlan($membershipPlan)
	{
		return $this->createMembershipPlanForm($membershipPlan);
	}
	public function getEditingFormForMembershipPlan($membershipPlan)
	{
		$form = $this->createMembershipPlanForm($membershipPlan);
		$form->makeDisabled('type');

		return $form;
	}
	public function getQuantityOfContractsByMembershipPlanSID($membershipPlanSID)
	{
		return $this->dbManager->getQuantityOfContractsByMembershipPlanSID($membershipPlanSID);
	}
	public function deleteMembershipPlanBySID($membershipPlanSID)
	{
		if ($this->getQuantityOfContractsByMembershipPlanSID($membershipPlanSID) > 0)
		{
			throw new MembershipPlanException('CANNOT_DELETE_MEMBERSHIP_HAS_CONTRACTS');
		}

		$this->dbManager->deleteFromRelations($membershipPlanSID);
		\App()->PackageManager->deletePackagesByMembershipPlanSID($membershipPlanSID);

		return $this->dbManager->deleteMembershipPlanBySID($membershipPlanSID);
	}
	public function getAllMembershipPlansInfo()
	{
		return $this->dbManager->getAllMembershipPlansInfo();
	}
	public function getAllMembershipPlansInfoWithPackagesInfo()
	{
		$plans = $this->getAllMembershipPlansInfo();
		foreach(array_keys($plans) as $key)
		{
			$plans[$key]['packages'] = \App()->PackageManager->getPackagesByMembershipPlanSID($plans[$key]['sid']);
		}

		return $plans;
	}
	public function getMembershipPlanInfoBySID($membershipPlanSID)
	{
		return $this->dbManager->getMembershipPlanInfoBySID($membershipPlanSID);
	}
	public function getAllMembershipPlanSIDsByUserGroupSID($userGroupSID)
	{
		return $this->dbManager->getAllMembershipPlanSIDsByUserGroupSID($userGroupSID);
	}
	public function getAllMembershipPlansIDsAndCaptions()
	{
		return array_map(function($membershipPlanInfo)
		{
			return array('id' => $membershipPlanInfo['sid'], 'caption' => $membershipPlanInfo['name']);
		}, $this->getAllMembershipPlansInfo());
	}
	public function createTemplateStructureForMembershipPlan($membershipPlanSID)
	{
		$membershipPlan = $this->getMembershipPlanWithPackagesBySID($membershipPlanSID);
		$AllPackageInfo = $membershipPlan->getPropertyValue('packages');
		$packageTemplateStructure = array();
		$packageTypes = \App()->PackageManager->getPackageTypes();
		foreach($AllPackageInfo as $packageInfo)
		{
			$package = $packageInfo->getObject();
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

		$membershipPlan->setPropertyValue('packages', $packageTemplateStructure);

    	return $membershipPlan;
	}

	public function getSearch($request)
	{
		$search = new \lib\ORM\SearchEngine\Search();
		$search->setDB(\App()->DB);
		$search->setRowMapper(new \modules\membership_plan\lib\MembershipPlan\MembershipPlanManagerToRowMapperAdapter());
		$search->setModelObject($this->createMembershipPlan(array()));
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		$search->setRequest($request);

		$search->setPage(1);
		$search->setObjectsPerPage(1000);

		return $search;
	}
}
