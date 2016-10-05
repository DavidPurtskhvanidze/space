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

class ManagePicturesHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'manage_pictures';

	public function respond()
	{

		$listing_id = isset($_REQUEST['listing_id']) ? $_REQUEST['listing_id'] : null;
		$searchId = isset($_REQUEST['searchId']) ? $_REQUEST['searchId'] : null;
		$errors = null;
		$field_errors = null;
		$messages = isset($_REQUEST['message']) ? array(array('content' => $_REQUEST['message'])) : array();

		if (is_null($listing_id))
		{
			$errors['PARAMETERS_MISSED'] = 1;
		}
		else
		{
			$listing = \App()->ListingManager->getObjectBySID($listing_id);
			$listing_info = \App()->ListingManager->getListingInfoBySID($listing_id);

			if (is_null($listing))
			{
				$errors['WRONG_PARAMETERS_SPECIFIED'] = 1;
			}
			else
			{
                $objectToArrayAdapter = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($listing);
                $template_processor = \App()->getTemplateProcessor();
                $template_processor->assign("listing", $objectToArrayAdapter);
                $template_processor->assign("errors", $errors);
				$template_processor->assign("field_errors", $field_errors);
				$template_processor->assign("listing_id", $listing_id);
				$template_processor->assign("listing_info", $listing_info);
				$template_processor->assign("searchId", $searchId);
				$template_processor->assign("messages", $messages);

				$template_processor->display("manage_pictures.tpl");
			}
		}
	}
}
