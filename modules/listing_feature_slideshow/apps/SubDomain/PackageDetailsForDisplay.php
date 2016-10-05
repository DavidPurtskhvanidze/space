<?php
/**
 *
 *    Module: listing_feature_slideshow v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_slideshow-7.5.0-1
 *    Tag: tags/7.5.0-1@19792, 2016-06-17 13:19:49
 *
 *    This file is part of the 'listing_feature_slideshow' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_slideshow\apps\SubDomain;

class PackageDetailsForDisplay implements \modules\membership_plan\apps\FrontEnd\IPackageDetailsForDisplay
{
	/**
	 * Package
	 * @var \modules\membership_plan\lib\Package\Package
	 */
	private $package;
	
	public static function getOrder()
	{
		return 400;
	}
	public function setPackage($package)
	{
		$this->package = $package;
	}
	public function perform()
	{
		$detailsToDislplay = array();

		$property = $this->package->getProperty('feature_slideshow_price');
		$detailsToDislplay['feature_slideshow_price'] = array(
			'caption' => $property->getCaption(),
			'value' => $property->getDisplayValue() ? $property->getDisplayValue() : 0,
		);

		return $detailsToDislplay;
	}
}
