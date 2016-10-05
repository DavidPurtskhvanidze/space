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


namespace modules\classifieds\apps\SubDomain\scripts;

class DisplayListingHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Display Listing';
	protected $moduleName = 'classifieds';
	protected $functionName = 'display_listing';
	protected $parameters = array('display_template');


	public function respond()
	{
        $listingSid = \modules\classifieds\lib\Listing\DisplayHelper::getInstance(\App()->Request->getRequest())->getListingId();
        $listingInfo = \App()->ListingManager->getListingInfoBySID($listingSid);
        if ($listingInfo['user_sid'] != \App()->Dealer['user_sid'])
            \modules\classifieds\lib\Listing\DisplayHelper::getInstance(\App()->Request->getRequest())->setListingId(null);

        $frontEndHandler = new \modules\classifieds\apps\FrontEnd\scripts\DisplayListingHandler();
        $frontEndHandler->respond();
	}
}
