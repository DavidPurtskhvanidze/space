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


namespace modules\membership_plan\apps\AdminPanel;

class PackageDetailsForDisplay implements \modules\membership_plan\apps\AdminPanel\IPackageDetailsForDisplay
{
	/**
	 * Package
	 * @var \modules\membership_plan\lib\Package\Package
	 */
	private $package;
	
	public static function getOrder()
	{
		return 100;
	}
	public function setPackage($package)
	{
		$this->package = $package;
	}
	public function perform()
	{
		$detailsToDislplay = array();

		$property = $this->package->getProperty('price');
		$detailsToDislplay['price'] = array(
			'caption' => $property->getCaption(),
			'value' => $property->getDisplayValue(),
		);
		$property = $this->package->getProperty('listing_lifetime');
		$detailsToDislplay['listing_lifetime'] = array(
			'caption' => $property->getCaption(),
			'value' => $property->getDisplayValue(),
		);
		$property = $this->package->getProperty('pic_limit');
		$detailsToDislplay['pic_limit'] = array(
			'caption' => $property->getCaption(),
			'value' => $property->getDisplayValue(),
		);
		$property = $this->package->getProperty('video_allowed');
		$detailsToDislplay['video_allowed'] = array(
			'caption' => $property->getCaption(),
			'value' => ($property->getValue()) ? 'Yes' : 'No',
		);
		
		return $detailsToDislplay;
	}
}
