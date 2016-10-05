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

use \modules\classifieds\apps\FrontEnd\IAfterAddListingAction as IAfterUserAddListingAction;
use \modules\classifieds\apps\AdminPanel\IAfterAddListingAction as IAfterAdminAddListingAction;

class AfterAddListingAction implements IAfterUserAddListingAction, IAfterAdminAddListingAction
{
    /**
     * @var \modules\classifieds\lib\Listing\Listing
     */
    private $listing;

    /**
     * Setter of listing
     * @param \modules\classifieds\lib\Listing\Listing $listing
     */
    public function setListing($listing)
    {
        $this->listing = $listing;
    }

    /**
     * Action executer
     */
    public function perform()
    {
        $action = \App()->ObjectMother->getCategoryCountAllListingAction($this->listing->getCategorySID());
        $action->increment();
    }
}
