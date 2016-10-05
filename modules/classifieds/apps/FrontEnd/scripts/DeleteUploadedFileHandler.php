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

class DeleteUploadedFileHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Delete Uploaded File';
	protected $moduleName = 'classifieds';
	protected $functionName = 'delete_uploaded_file';

	/**
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;
	private $field_id;

	public function respond()
	{
		try
		{
			$this->init();

			$uploaded_file_info = $this->listing->getPropertyValue($this->field_id);
			$uploaded_file_id = $uploaded_file_info['file_id'];
			\App()->UploadFileManager->deleteUploadedFileByID($uploaded_file_id);
			\App()->ListingManager->updateListingPartially($this->listing, array($this->field_id => ""));
		}
		catch (\modules\main\lib\HandlerException $e)
		{
			$tp = \App()->getTemplateProcessor();
			$tp->assign('error', $e->getMessage());
			throw new \lib\Http\ForbiddenException($tp->fetch('delete_uploaded_file_error.tpl'));
		}
	}

	private function init()
	{
		$listing_id = isset($_REQUEST['listing_id']) ? $_REQUEST['listing_id'] : null;
		$this->field_id = isset($_REQUEST['field_id']) ? $_REQUEST['field_id'] : null;
		if (is_null($listing_id) || is_null($this->field_id))
		{
			throw new \modules\main\lib\HandlerException('PARAMETERS_MISSED');
		}
		if (is_null($this->listing = \App()->ListingManager->getObjectBySID($listing_id)))
		{
			throw new \modules\main\lib\HandlerException('WRONG_PARAMETERS_SPECIFIED');
		}
		if (!$this->listing->propertyIsSet($this->field_id))
		{
			throw new \modules\main\lib\HandlerException('WRONG_PARAMETERS_SPECIFIED');
		}
		if ($this->listing->getUserSID() != \App()->UserManager->getCurrentUserSID())
		{
			throw new \modules\main\lib\HandlerException('NOT_OWNER');
		}
	}
}
