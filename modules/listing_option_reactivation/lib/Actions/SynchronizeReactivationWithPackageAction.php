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


namespace modules\listing_option_reactivation\lib\Actions;

class SynchronizeReactivationWithPackageAction
{
	/**
	 * Package Sid
	 * @var int
	 */
	private $packageSid;
	
	public function setPackageSid($packageSid)
	{
		$this->packageSid = $packageSid;
	}
	
	public function perform()
	{
		$packageInfo = \App()->PackageManager->getPackageInfoBySID($this->packageSid);
		\App()->ListingReactivationManager->updatePackgeInfoByPackageSid($this->packageSid, $packageInfo);
	}
}
