<?php
/**
 *
 *    Module: listing_option_reactivation v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_option_reactivation-7.5.0-1
 *    Tag: tags/7.5.0-1@19794, 2016-06-17 13:19:54
 *
 *    This file is part of the 'listing_option_reactivation' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_option_reactivation\lib;

class ListingReactivation extends \lib\ORM\Object
{
	public function getPackageInfo()
	{
		return $this->getPropertyValue('package_info');
	}

	public function isActive()
	{
		return (bool) $this->getPropertyValue('activated');
	}
	
	public function getOptionsToActivate()
	{
		return $this->getPropertyValue('options_to_activate');
	}
	
	public function setOptionsToActivate($options)
	{
		return $this->setPropertyValue('options_to_activate', $options);
	}
	
	public function getListingSid()
	{
		return $this->getPropertyValue('listing_sid');
	}
	
	public function getActivationPrice()
	{
		$packageInfo = $this->getPropertyValue('package_info');
		return intval($packageInfo['price']);
	}

	public function getTotalPrice()
	{
		$packageInfo = $this->getPropertyValue('package_info');
		$optionsToActivate = $this->getPropertyValue('options_to_activate');
		
		$totalPrice = $this->getActivationPrice();
		$paidFeatures = \App()->ListingFeaturesManager->getPaidFeaturesByPackageInfo($packageInfo);
		foreach($optionsToActivate as $optionId)
		{
			if (isset($paidFeatures[$optionId]))
			{
				$totalPrice += intval($paidFeatures[$optionId]['price']);
			}
		}

		return $totalPrice;
	}
}
