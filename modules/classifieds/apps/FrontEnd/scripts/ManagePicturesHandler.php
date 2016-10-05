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

class ManagePicturesHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Manage Pictures';
	protected $moduleName = 'classifieds';
	protected $functionName = 'manage_pictures';

	/**
	 * @var \modules\classifieds\lib\ListingGallery\ListingGallery
	 */
	private $listingGallery;

	/**
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		try
		{
			$this->init();
			$packageInfo = $this->listing->getListingPackageInfo();
			$objectToArrayAdapter = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this->listing);
			$template_processor->assign("listing", $objectToArrayAdapter);
			$template_processor->assign("numberOfPicturesAllowed", $packageInfo['pic_limit']);
			$template_processor->display("manage_pictures.tpl");
		}
		catch (\modules\main\lib\HandlerException $e)
		{
			$template_processor->assign("error", $e->getMessage());
			$template_processor->display("manage_pictures_error.tpl");
		}
	}

	private function init()
	{
		$listing_id = isset($_REQUEST['listing_id']) ? $_REQUEST['listing_id'] : null;
		if (is_null($listing_id))
		{
			throw new \modules\main\lib\HandlerException('PARAMETERS_MISSED');
		}
		if (is_null($this->listing = \App()->ListingManager->getObjectBySID($listing_id)))
		{
			throw new \modules\main\lib\HandlerException('WRONG_PARAMETERS_SPECIFIED');
		}
		if ($this->listing->getUserSID() != \App()->UserManager->getCurrentUserSID())
		{
			throw new \modules\main\lib\HandlerException('NOT_OWNER');
		}
		$packageInfo = $this->listing->getListingPackageInfo();
		if ($packageInfo['pic_limit'] == 0)
		{
			throw new \modules\main\lib\HandlerException('PICTURES_NOT_ALLOWED_BY_PACKAGE');
		}
	}
}
