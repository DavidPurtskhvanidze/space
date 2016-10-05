<?php
/**
 *
 *    Module: listing_feature_highlighted v.7.0.0-1, (c) WorksForWeb 2005 - 2014
 *
 *    Package: listing_feature_highlighted-7.0.0-1
 *    Tag: tags/7.0.0-1@16269, 2014-10-03 12:00:56
 *
 *    This file is part of the 'listing_feature_highlighted' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_highlighted\apps\FrontEnd;

class PackageDetailsForDisplay implements \modules\membership_plan\apps\FrontEnd\IPackageDetailsForDisplay
{
	/**
	 * Package
	 * @var \modules\membership_plan\lib\Package\Package
	 */
	private $package;
	
	public static function getOrder()
	{
		return 200;
	}
	public function setPackage($package)
	{
		$this->package = $package;
	}
	public function perform()
	{
		$detailsToDislplay = array();

		$property = $this->package->getProperty('feature_highlighted_price');
		$detailsToDislplay['feature_highlighted_price'] = array(
			'caption' => $property->getCaption(),
			'value' => $property->getDisplayValue() ? $property->getDisplayValue() : 0,
		);

		return $detailsToDislplay;
	}
}
