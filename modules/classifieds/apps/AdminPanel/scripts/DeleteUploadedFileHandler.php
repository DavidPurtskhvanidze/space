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

class DeleteUploadedFileHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'delete_uploaded_file';

	public function respond()
	{

	$listing_id = isset($_REQUEST['listing_id']) ? $_REQUEST['listing_id'] : null;

	$listing_info = \App()->ListingManager->getListingInfoBySID($listing_id);

	$field_id = isset($_REQUEST['field_id']) ? $_REQUEST['field_id'] : null;

	if (is_null($listing_id) || is_null($field_id))
	{
		\App()->ErrorMessages->addMessage('PARAMETERS_MISSED');
	}
	elseif (is_null($listing_info) || !isset($listing_info[$field_id]))
	{
		\App()->ErrorMessages->addMessage('WRONG_PARAMETERS_SPECIFIED');	
	}
	else
	{

		$uploaded_file_id = $listing_info[$field_id];

		\App()->UploadFileManager->deleteUploadedFileByID($uploaded_file_id);

		$listing_info[$field_id] = "";

		$listing = \App()->ObjectMother->getListingFactory()->getListing($listing_info, $listing_info['category_sid']);
		$listing->setSID($listing_id);

		$propertiesToExclude = array('activation_date', 'sid', 'type', 'category_sid', 'active', 'id', 'moderation_status', 'views', 'package', 'pictures', 'user', 'username');
		array_walk($propertiesToExclude, array($listing, 'deleteProperty'));

		\App()->ListingManager->saveListing($listing);
		
		\App()->SuccessMessages->addMessage('FILE_DELETED');	

	}

	$template_processor = \App()->getTemplateProcessor();

	$template_processor->assign("listing_id", $listing_id);

	$template_processor->display("delete_uploaded_file.tpl");

	}
}

?>
