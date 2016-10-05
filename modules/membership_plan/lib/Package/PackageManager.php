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

class PackageManager implements \core\IService
{
	/**
	 * @var PackageDBManager
	 */
	private $dbManager;

	private $defaultPackageClassName = '\\modules\\classifieds\\lib\\ListingPackage';

	private $packageTypes = null;

	public function init()
	{
		$this->dbManager = new \modules\membership_plan\lib\Package\PackageDBManager();
	}
	public function getPackageTypes()
	{
		if (!is_null($this->packageTypes)) return $this->packageTypes;

		$types =  [];
		$externalTypes = new \core\ExtensionPoint('modules\membership_plan\lib\IExternalPackageType');
		foreach($externalTypes as $externalType)
		{
			$types[$externalType->getTypeID()] = $externalType->getData();
		}
		$this->packageTypes = $types;

		return $this->packageTypes;
	}

	public function createPackage($packageSID, $className, $membershipPlanSID, $packageData)
	{
		$packageTypes = $this->getPackageTypes();
		$package = new Package();
		$package->setDetails($this->createPackageDetails($packageTypes[$className]['package_class']));
		$package->incorporateData($packageData);

		$package->setSid($packageSID);
		$package->setClassName($className);
		$package->setMembershipPlanSID($membershipPlanSID);

		return $package;
	}
	public function createPackageFromListingInfo($packageInfo)
	{
		$packageTypes = $this->getPackageTypes();
		$packageClassName = empty($packageTypes[$packageInfo['class_name']]['package_class'])
			? $this->defaultPackageClassName
			: $packageTypes[$packageInfo['class_name']]['package_class'];
		$package = new Package();
		$package->setDetails($this->createPackageDetails($packageClassName));
		$package->incorporateData($packageInfo);

		$package->setSid($packageInfo['sid']);
		$package->setClassName($packageInfo['class_name']);
		$package->setMembershipPlanSID($packageInfo['membership_plan_sid']);

		return $package;
	}
	private function createPackageDetails($packageDetailsClassName)
	{
		$packageDetailsClassName = empty($packageDetailsClassName) ? $this->defaultPackageClassName : $packageDetailsClassName;
		$details = new $packageDetailsClassName;
		$details->setDetailsInfo($this->getDetails($packageDetailsClassName));
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildProperties();

		return $details;
	}
	public function getDetails($packageDetailsClass)
	{
		return $packageDetailsClass::getSystemDetailsInfo();
	}
	private function createPackageForm($package)
	{
		return \App()->ObjectMother->createForm($package);
	}
	public function getCreatingFormForPackage($package)
	{
		return $this->createPackageForm($package);
	}
	public function getEditingFormForPackage($package)
	{
		$form = $this->createPackageForm($package);

		return $form;
	}
	public function getEditListingPackageForm($package)
	{
		$package->getDetails()->modifyBoolenasToListWithIntactOption();
		$form = $this->createPackageForm($package);

		return $form;
	}

	public function savePackage($package)
	{
		$this->dbManager->savePackage($package);
	}
	public function getPackageBySID($packageSID)
	{
		$packageInfo = $this->dbManager->getPackageInfoBySID($packageSID);
		if (empty($packageInfo))
		{
			throw new PackageException('RECORD_NOT_FOUND');
		}

		$package = $this->createPackage($packageInfo['sid'], $packageInfo['class_name'], $packageInfo['membership_plan_sid'], $packageInfo);

		return $package;
	}
	public function getPackageInfoBySID($packageSID)
	{
		return $this->dbManager->getPackageInfoBySID($packageSID);
	}
	public function getPackagesByMembershipPlanSID($membershipPlanSID)
	{
		$results = $this->dbManager->getPackagesInfoByMembershipPlanSID($membershipPlanSID);
		foreach ($results as &$packageInfo)
		{
			$package = $this->createPackage($packageInfo['sid'], $packageInfo['class_name'], $packageInfo['membership_plan_sid'], $packageInfo['fields']);
			$package->addNumberOfListingsProperty($this->getListingQuantityByPackageSID($package->getSID()));
			$packageInfo = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($package);
		}

		return $results;
	}

	public function getGroupedTypePackagesByMembershipPlanSID($membershipPlanSID)
	{
		$packages = $this->dbManager->getPackagesInfoByMembershipPlanSID($membershipPlanSID);
		$types = $this->getPackageTypes();
		$result = [];
		foreach($packages as $packageInfo)
		{
			$package = $this->createPackage($packageInfo['sid'], $packageInfo['class_name'], $packageInfo['membership_plan_sid'], $packageInfo['fields']);
			$package->addNumberOfListingsProperty($this->getListingQuantityByPackageSID($package->getSID()));
			$result[$packageInfo['class_name']]['packages'][] =  \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($package);
			$result[$packageInfo['class_name']]['typeData'] = $types[$packageInfo['class_name']];
		}
		return $result;
	}

	function getPackagesInfoByMembershipPlanIdGroupByClass($membershipPlanSID)
	{
		$packagesInfo = $this->dbManager->getPackagesInfoByMembershipPlanSID($membershipPlanSID);
		$packagesIndexedByClass = array();
		foreach ($packagesInfo as $packageInfo)
		{
			$tmpPackageInfo = array_merge($packageInfo, $packageInfo['fields']);
			unset($tmpPackageInfo['fields']);
			$packagesIndexedByClass[$packageInfo['class_name']][] = $tmpPackageInfo;
		}

		return $packagesIndexedByClass;
	}
	public function getListingQuantityByPackageSID($packageSID)
	{
		return $this->dbManager->getListingQuantityByPackageSID($packageSID);
	}
	function deletePackagesByMembershipPlanSID($membershipPlanSID)
	{
		return $this->dbManager->deletePackagesByMembershipPlanSID($membershipPlanSID);
	}
	function deletePackageBySID($sid)
	{
		return $this->dbManager->deletePackageBySID($sid);
	}

	function getPackagesByClass($class_name)
	{
		$packages = \App()->DB->query("SELECT * FROM `membership_plan_packages` WHERE `class_name` = ?s", $class_name);

		foreach ($packages as $key => $package)
		{
			$package_fields = stripslashes($package['fields']);
			$packages[$key] = array_merge($package, unserialize($package_fields));
		}

		return $packages;
	}

	public function getAllPackagesListValues()
	{
		$plans = \App()->MembershipPlanManager->getAllMembershipPlansInfoWithPackagesInfo();
		$packagesListValues = array();
		foreach ($plans as $planInfo)
		{
			$packages = $planInfo['packages'];
			foreach ($packages as $packageInfo)
			{
				$packagesListValues[] = array('sid' => $packageInfo['sid'], 'caption' => $packageInfo['name'], 'parent_name' => $planInfo['name']);
			}
		}
		return $packagesListValues;
	}

	public function getFreePackageFeatures()
	{
		$packages = $this->getPackagesByClass('ListingPackage');
		$freePackageFeatures = array();
		foreach ($packages as $package)
		{
			$freePackageFeatures[$package['sid']] = \App()->ListingFeaturesManager->getFreeFeaturesByPackageInfo($package);
		}
		return $freePackageFeatures;
	}

	public function getAllPackagesInfo()
	{
		$allPackagesInfo = \App()->DB->query("SELECT *, `sid` AS `package_sid` FROM `membership_plan_packages`");
		$allPackagesInfo = array_map(function ($packageInfo)
		{
			$packageInfo = array_merge(unserialize($packageInfo['fields']), $packageInfo);
			unset($packageInfo['fields']);
			return $packageInfo;
		}, $allPackagesInfo);
		return $allPackagesInfo;
	}

	public function getAllPackagesIterator()
	{
		$packagesIterator = new \lib\ORM\ObjectsInfoToObjectsIterator();
		$packagesIterator->setRowMapperCallback(array($this, 'createPackageFromListingInfo'));
		$packagesIterator->setObjectsInfo($this->getAllPackagesInfo());
		return $packagesIterator;
	}
	
	public function setPackageDisplayOrder($membershipPlanSid, $newOrder)
	{
		foreach ($newOrder as $order => $packageSid)
		{
			\App()->DB->query("UPDATE `membership_plan_packages` SET `order`=?n WHERE `sid`=?n AND `membership_plan_sid`=?n", $order, $packageSid, $membershipPlanSid);
		}
	}
}
