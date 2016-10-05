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

class Contract
{
	private $sid;
	private $type;
	private $price;
	private $membershipPlanSID;
	private $expiredDate;
	private $autoExtend = false;
	private $packagesInfo;
	private $extraInfo;

	public function getExpiredDate()
	{
		return $this->expiredDate;
	}
	public function getExtraInfo()
	{
		return $this->extraInfo;
	}
	public function setSID($sid)
	{
		$this->sid = $sid;
	}
	public function getSID() {
		return $this->sid;
	}
	public function getId() {
		return $this->getSID();
	}
	public function setPackagesInfo($packagesInfo)
	{
		$this->packagesInfo = $packagesInfo;
	}
	public function getPackagesInfo()
	{
		return $this->packagesInfo;
	}
    public function isExpired()
    {
		if (empty($this->expiredDate))
		{
			return false;
		}
		else
		{
			return new \DateTime($this->expiredDate) < new \DateTime();
		}
    }
	public function getHashedFields() {
		$fields['membership_plan_sid'] 	= $this->membershipPlanSID;
		$fields['type']					= $this->type;
		$fields['creation_date']		= date("Y-m-d");
		$fields['expired_date']			= $this->expiredDate;
		$fields['auto_extend']			= $this->autoExtend;
		return $fields;
	}
	public function getPrice() {
		
		return $this->price;
		
	}
	public function getPackagesInfoByClassName($class_name)
	{
		return isset($this->packagesInfo[$class_name]) ? $this->packagesInfo[$class_name] : array();
	}
	public function getPackageInfoByPackageSID($packageSID)
	{
		foreach ($this->packagesInfo as $class => $packages)
		{
			foreach ($packages as $packageInfo)
			{
				if ($packageInfo['sid'] == $packageSID)
				{
					return $packageInfo;
				}
			}
		}
		return null;
	}
	public function getAvailableListingsAmount()
	{
		return $this->extraInfo['classifieds_listing_amount'];
	}
	public function getListingPackagesInfo()
	{
		return $this->getPackagesInfoByClassName('ListingPackage');
	}
	public function getSubDomainPackagesInfo()
	{
		return $this->getPackagesInfoByClassName('SubDomainPackage');
	}
	public function isListingPackageAvailableBySID($packageSID)
	{
		$listing_packages_info = $this->getListingPackagesInfo();
		foreach ($listing_packages_info as $listing_package_info)
		{
			if ($listing_package_info['sid'] == $packageSID)
			{
				return true;
			}
		}
		return false;
	}
	public function isListingPackageAvailableByBasePackageSID($basePackageSID)
	{
		if (!strcasecmp($this->type, "Subscription"))
		{
			$field = "package_sid";
		}
		else
		{
			$field = "sid";
		}
		$listingPackagesBaseSID = array_map(create_function('$a', 'return $a["' . $field . '"];'), $this->getListingPackagesInfo());
		return in_array($basePackageSID, $listingPackagesBaseSID);
	}
    public function isAutoExtend()
    {
    	return (bool)$this->autoExtend;
    }
    public function getMembershipPlanSID()
    {
    	return $this->membershipPlanSID;
    }
    public function setAutoExtend($autoExtend)
    {
    	return $this->autoExtend = $autoExtend;
    }
    public function getType()
    {
    	return $this->type;
    }
    public function getContractPackageSID($packageSID)
    {
    	if (!strcasecmp($this->type, "Subscription"))
    	{
    		$myPackages = $this->getListingPackagesInfo();
    		foreach ($myPackages as $packageInfo) if ($packageInfo['package_sid'] == $packageSID ) return $packageInfo['sid'];
    		return null;
		}
		else
		{
			return $packageSID;
		}
    }
	public function buildPropertiesWithData($objectInfo)
	{
		$this->type = $objectInfo['type'];
		$this->price = $objectInfo['price'];
		$this->membershipPlanSID = $objectInfo['membership_plan_sid'];
		$this->autoExtend = isset($objectInfo['auto_extend']) ? $objectInfo['auto_extend'] : false;
		$this->expiredDate = $objectInfo['expired_date'];
		$this->extraInfo = is_null($objectInfo['serialized_extra_info']) ? null : unserialize($objectInfo['serialized_extra_info']);
	}
}
