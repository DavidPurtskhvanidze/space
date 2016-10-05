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

namespace modules\classifieds\apps\FrontEnd;

class AfterEditListingPendingAction implements IAfterEditListingAction
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
        if (\App()->ListingManager->getModerationStatus($this->listing->getSID()) == 'PENDING') {
            $userInfo = \App()->UserManager->getUserInfoBySID($this->listing->getUserSID());
            return \App()->EmailService->sendToAdmin(
                'email_template:admin_edit_listing_pending_email',
                [
                    'listing' => \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this->listing),
                    'user' => $userInfo
                ]
            );
        }
    }
}
