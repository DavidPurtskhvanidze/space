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

class Package extends \lib\ORM\Object
{
	private $className;
	private $membershipPlanSID;
	private $numberOfListings;

	public function setClassName($className)
	{
		$this->className = $className;
	}
	public function getClassName()
	{
		return $this->className;
	}
	public function setMembershipPlanSID($SID)
	{
		$this->membershipPlanSID = $SID;
	}
	public function getMembershipPlanSID()
	{
		return $this->membershipPlanSID;
	}

	public function addNumberOfListingsProperty($numberOfListings)
	{
		$this->details->addNumberOfListingsProperty($numberOfListings);
	}

	public function setNumberOfListings($numberOfListings)
	{
		$this->numberOfListings = $numberOfListings;
	}
	public function getNumberOfListings($numberOfListings)
	{
		return $this->numberOfListings;
	}

	function getHashedFields()
	{
		$properties = $this->getDetails()->getProperties();
		$hashedFields = array();
		foreach ($properties as $property)
		{
			if (!$property->saveIntoBD())
			{
				continue;
			}
			$hashedFields[$property->getID()] = $property->getValue();
		}

		return $hashedFields;
	}
}
