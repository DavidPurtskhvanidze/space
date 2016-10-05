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


namespace modules\classifieds\apps\AdminPanel\scripts;

// version 5 wrapper header

class EditListingHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'edit_listing';

	public function respond()
	{
		
// end of version 5 wrapper header





$listing_id = isset($_REQUEST['listing_id']) ? $_REQUEST['listing_id'] : null;
$searchId = isset($_REQUEST['searchId']) ? $_REQUEST['searchId'] : null;

$listing_info = \App()->ListingManager->getListingInfoBySID($listing_id);

if (!is_null($listing_info)) {
	$listing_info = \App()->ListingManager->listingInfoI18N($listing_info);
	$listing_info = array_merge($listing_info, $_REQUEST);
	
	$listing = \App()->ObjectMother->getListingFactory()->getListing($listing_info, $listing_info['category_sid']);
	$listing->setSID($listing_id);
	
	$propertiesToExclude = array('sid', 'user_sid', 'activation_date', 'type', 'category_sid', 'listing_package', 'expiration_date', 'active', 'id', 'moderation_status', 'views', 'package', 'pictures', 'user', 'username', 'keywords', 'category');
	array_walk($propertiesToExclude, array($listing, 'deleteProperty'));
	
	$listing_edit_form = new \lib\Forms\Form($listing);
	
	$action = (isset($_REQUEST['action'])) ? strtolower($_REQUEST['action']) : null;

	$userInfo = \App()->UserManager->getUserInfoBySID($listing->getUserSID());

	$messages = isset($_REQUEST['message']) ? array(array('content' => $_REQUEST['message'])) : array();

	if (($action == 'save_info') && $listing_edit_form->isDataValid()) {
		$canPerform = true;
		$validators = new \core\ExtensionPoint('modules\classifieds\apps\AdminPanel\IEditListingValidator');
		foreach ($validators as $validator)
		{
			$validator->setListing($listing);
			$canPerform &= $validator->isValid();
		}
		if ($canPerform)
		{
			\App()->ListingManager->saveListing($listing);
			$messages[] = array('content' => 'LISTING_SAVED');
		}
		
		$afterEditListingActions = new \core\ExtensionPoint('modules\classifieds\apps\AdminPanel\IAfterEditListingAction');
		foreach ($afterEditListingActions as $afterEditListingAction)
		{
			$afterEditListingAction->setListing($listing);
			$afterEditListingAction->perform();
		}
	}

	$template_processor = \App()->getTemplateProcessor();

	$listing_edit_form->registerTags($template_processor);

	$template_processor->assign("form_fields", $listing_edit_form->getFormFieldsInfo());
	
	$listingDisplayer = new \modules\classifieds\lib\Listing\ListingDisplayer();
	$listingDisplayer->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);

	$template_processor->assign("listing", $listingDisplayer->wrapListing($listing));
	$template_processor->assign("listing_id", $listing_id);
	$template_processor->assign("searchId", $searchId);
	$template_processor->assign("messages", $messages);

	$template_processor->assign("package", \App()->ListingPackageManager->createDisplayTemplateStructureForPackageByListingSID($listing_id));
	$template_processor->assign("userinfo_ip_address", $listing_info['last_user_ip']);

    $template_processor->assign("userinfo_sid", $userInfo['sid']);
    $template_processor->assign("userinfo_username", $userInfo['username']);
    $template_processor->assign("userinfo_trusted", $userInfo['trusted_user']);
    $template_processor->assign("listing_info", $listing_info);

	$template_processor->display("edit_listing.tpl");
	
}


//  version 5 wrapper footer

	}
}
