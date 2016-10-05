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

/**
 * @property MembershipPlanDetails $details
 */
class MembershipPlan extends \lib\ORM\Object
{
	public function getType()
	{
		return $this->getPropertyValue('type');
	}
	public function getPrice()
	{
		return $this->getPropertyValue('price');
	}
	public function getName()
	{
		return $this->getPropertyValue('name');
	}
	public function getSubscriptionPeriod()
	{
		return $this->getPropertyValue('subscription_period');
	}
	
	public function addSerializedExtraInfoProperty($serializedExtraInfo = array())
	{
		$this->details->addSerializedExtraInfoProperty($serializedExtraInfo);
	}
	public function deleteSerializedExtraInfoProperty($serializedExtraInfo = array())
	{
		$this->details->deleteSerializedExtraInfoProperty($serializedExtraInfo);
	}
	public function addQuantityOfContractsProperty($quantity)
	{
		$this->details->addQuantityOfContractsProperty($quantity);
	}
	public function addPackagesProperty($packages)
	{
		$this->details->addPackagesProperty($packages);
	}

	function getHashedFields()
	{
		$properties = $this->getDetails()->getProperties();
		$hashedFields = array();
		$skipProperties = array('sid');
		foreach ($properties as $property)
		{
			if (!in_array($property->getID(), $skipProperties))
			{
				$hashedFields[$property->getID()] = $property->getValue();
			}
		}

		return $hashedFields;
	}
}
