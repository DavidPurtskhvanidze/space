<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib;

class ExpireUserListingsAction
{
	var $listingsSid;

	function setListingsSid($listingsSid)
	{
		$this->listingsSid = $listingsSid;
	}

	function perform()
	{
		foreach ($this->listingsSid as $listingSid)
		{
			\App()->ListingManager->deactivateListingBySID($listingSid);
			\App()->ListingFeaturesManager->deactivateAllFeatures(\App()->ListingManager->getObjectBySID($listingSid));
			if (\App()->ListingManager->getModerationStatus($listingSid) != 'APPROVED')
			{
				\App()->ListingManager->setModerationStatus($listingSid, null);
			}
		}

        $afterImportListingAction = new \Core\ExtensionPoint('modules\classifieds\lib\Listing\IAfterExpireListingsAction');
        foreach($afterImportListingAction as $action)
        {
            $action->setListingsSid($this->listingsSid);
            $action->perform();
        }
	}
}
