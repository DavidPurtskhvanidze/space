<?php
/**
 *
 *    Module: listing_feature_sponsored v.7.4.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_sponsored-7.4.0-1
 *    Tag: tags/7.4.0-1@19153, 2016-01-11 11:20:12
 *
 *    This file is part of the 'listing_feature_sponsored' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_sponsored\apps\FrontEnd;

class PackageDetailsForDisplay implements \modules\membership_plan\apps\FrontEnd\IPackageDetailsForDisplay
{
	/**
	 * Package
	 * @var \modules\membership_plan\lib\Package\Package
	 */
	private $package;
	
	public static function getOrder()
	{
		return 500;
	}
	public function setPackage($package)
	{
		$this->package = $package;
	}
	public function perform()
	{
		$detailsToDislplay = array();

		$property = $this->package->getProperty('feature_sponsored_price');
		$detailsToDislplay['feature_sponsored_price'] = array(
			'caption' => $property->getCaption(),
			'value' => $property->getDisplayValue() ? $property->getDisplayValue() : 0,
		);
		$property = $this->package->getProperty('feature_sponsored_lifetime');
		$detailsToDislplay['feature_sponsored_lifetime'] = array(
			'caption' => $property->getCaption(),
			'value' => $property->getDisplayValue(),
		);

		return $detailsToDislplay;
	}
}
