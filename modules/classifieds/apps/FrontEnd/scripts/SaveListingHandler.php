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


namespace modules\classifieds\apps\FrontEnd\scripts;

// version 5 wrapper header

class SaveListingHandler extends \apps\FrontEnd\ContentHandlerBase
{
    protected $displayName = 'Save Listing';
    protected $moduleName = 'classifieds';
    protected $functionName = 'save_listing';
    protected $rawOutput = true;

    public function respond()
    {

// end of version 5 wrapper header

        $savedListings = \App()->ObjectMother->createSavedListings();

        $listing_id = isset($_REQUEST['listing_id']) ? $_REQUEST['listing_id'] : null;

        $error = null;

        if (!is_null($listing_id))
        {
            $savedListings->saveListing($listing_id);
        }
        else
        {
            header($_SERVER['SERVER_PROTOCOL'] . ' 409 Conflict');
            $error = 'LISTING_ID_NOT_SPECIFIED';
        }

        $template_processor = \App()->getTemplateProcessor();
        $template_processor->assign("error", $error);
        $template_processor->assign("savedListingsAmount", count($savedListings->getSavedListings()));

        $template_processor->display("save_listing.tpl");
//  version 5 wrapper footer

    }
}

// end of version 5 wrapper footer
?>
