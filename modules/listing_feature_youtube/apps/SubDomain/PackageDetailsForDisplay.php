<?php
/**
 *
 *    Module: listing_feature_youtube v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_youtube-7.5.0-1
 *    Tag: tags/7.5.0-1@19793, 2016-06-17 13:19:51
 *
 *    This file is part of the 'listing_feature_youtube' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_youtube\apps\SubDomain;

use modules\membership_plan\apps\FrontEnd\IPackageDetailsForDisplay;

class PackageDetailsForDisplay implements IPackageDetailsForDisplay
{
	/**
	 * Package
	 * @var \modules\membership_plan\lib\Package\Package
	 */
	private $package;
	
	public static function getOrder()
	{
		return 300;
	}
	public function setPackage($package)
	{
		$this->package = $package;
	}
	public function perform()
	{
		$detailsToDislplay = [];

		$property = $this->package->getProperty('feature_youtube_price');
		$detailsToDislplay['feature_youtube_price'] = [
			'caption' => $property->getCaption(),
			'value' => $property->getDisplayValue() ? $property->getDisplayValue() : 0,
        ];

		return $detailsToDislplay;
	}
}
