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

class ManageListingOptionsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Manage Listing Options';
	protected $moduleName = 'classifieds';
	protected $functionName = 'manage_listing_options';
	protected $rawOutput = true;

	public function respond()
	{
		if (is_null(\App()->Request['listing_sid']))
		{
			throw new \lib\Http\NotFoundException("Listing sid was not specified");
		}
		$listing = \App()->ListingManager->getObjectBySID(\App()->Request['listing_sid']);
		if (is_null($listing))
		{
			throw new \lib\Http\NotFoundException("Listing with the specified sid was not found");
		}
		elseif ($listing->getUserSID() != \App()->UserManager->getCurrentUserSID())
		{
			throw new \lib\Http\ForbiddenException('You are not the owner of the listing');
		}
		if ($listing->isPendingApproval() || $listing->isRejected())
		{
			throw new \modules\classifieds\lib\Exception("The listing moderation status does not allow you to manage it's options. The listing should be approved or never activated before");
		}

		$contractSid = \App()->UserManager->getCurrentUser()->getContractID();
		$userContract = \App()->ContractManager->getContractBySID($contractSid);

		$handlerContext = new ManageListingOptionsHandlerContext();
		$handlerLogger = new ManageListingOptionsHandlerLogger();

		$handlerContext->setListing($listing);
		$handlerContext->setUserContract($userContract);

		$actions = new ManageListingOptionsHandlerActions();
		$actions->setContext($handlerContext);
		$actions->setLogger($handlerLogger);

		$process = new \modules\classifieds\lib\Actions\ChooseListingPackageProcess();
		$process->setContext($handlerContext);
		$process->setActions($actions);
		$process->perform();
	}
}
