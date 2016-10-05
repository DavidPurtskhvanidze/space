<?php
/**
 *
 *    Module: basket v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: basket-7.5.0-1
 *    Tag: tags/7.5.0-1@19771, 2016-06-17 13:18:56
 *
 *    This file is part of the 'basket' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\basket\lib\Actions;

class SynchronizeBasketItemsWithPackageAction
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
		$freeOptionIds = \App()->ListingFeaturesManager->getFreeFeatureIdsByPackageInfo($packageInfo);
		if (!empty($freeOptionIds))
		{
			$listingSids = \App()->ListingPackageManager->getListingSIDsByPackageSID($this->packageSid);
            if(empty($listingSids))
                return;
			$packageInfo['price'] = (float) $packageInfo['price'];
			if (empty($packageInfo['price']))
			{
				$freeOptionIds[] = 'activation';
			}
			\App()->BasketItemManager->deleteItemByListingSidsAndOptionIds($listingSids, $freeOptionIds);
		}
	}
}
