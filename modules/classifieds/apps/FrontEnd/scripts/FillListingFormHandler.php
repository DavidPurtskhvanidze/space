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

class FillListingFormHandler extends \apps\FrontEnd\ContentHandlerBase
{
    protected $displayName = 'Fill Listing form';
    protected $moduleName = 'classifieds';
    protected $functionName = 'fill_listing_form';
    protected $rawOutput = true;
    protected $parameters = array('category_id');


    public function respond()
    {
        $template_processor = \App()->getTemplateProcessor();

        if (!\App()->UserManager->isUserLoggedIn())
        {
            $errors['NOT_LOGGED_IN'] = true;
            $template_processor->assign("ERRORS", $errors);
            $template_processor->display("errors.tpl");
            return;
        }

        if (!is_null(\App()->Request['listing']))
        {
            $urlParams = [
                'fill_from_listing' => \App()->Request['listing'],
                'add_listing_session_id' => \App()->Request['add_listing_session_id']
            ];

            $url = \App()->PageRoute->getPagePathById('listing_add') . '?'  . http_build_query($urlParams);

            throw new \lib\Http\RedirectException($url);
        }

        $current_user_sid = \App()->UserManager->getCurrentUserSID();
        $_REQUEST['user_sid'] = array('equal' => $current_user_sid);
        $_REQUEST['active_only'] = 0;

        if (isset($_REQUEST['category_id']))
        {
            $_REQUEST['category_sid']['tree'][0] = \App()->CategoryManager->getCategorySIDByID($_REQUEST['category_id']);;
        }

        if (!isset($_REQUEST['restore']) &&!isset($_REQUEST['action']))
        {
            $_REQUEST['action'] = 'search';
        }

        $userTotalListingNumber = \App()->ListingManager->getListingsCountByUserSID($current_user_sid);
        $template_processor->assign('userTotalListingNumber', $userTotalListingNumber);
        $template_processor->assign('add_listing_session_id', \App()->Request['add_listing_session_id']);
        $template_processor->display('category_templates/input/fill/my_listings_main.tpl');
    }
}
