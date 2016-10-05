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

namespace modules\classifieds\lib\Category;

use modules\classifieds\lib\Listing\IAfterDeactivateListingsAction;
use modules\classifieds\lib\Listing\IAfterExpireListingsAction;

class AfterDeactivateListingsAction implements IAfterExpireListingsAction, IAfterDeactivateListingsAction
{
    /**
     * @var array
     */
    private $listingSids = [];

    /**
     * Action executer
     */
    public function perform()
    {
        foreach($this->listingSids as &$sid)
        {
            //todo:: problem n + 1 query
            $listingInfo = \App()->ListingManager->getListingInfoBySID($sid);
            $action = \App()->ObjectMother->getCategoryCountActiveListingAction($listingInfo['category_sid']);
            $action->decrement();
        }
    }

    public function setListingsSid($listingsSid)
    {
        $this->listingSids = $listingsSid;
    }
}
