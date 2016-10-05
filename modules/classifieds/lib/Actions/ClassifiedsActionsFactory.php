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


namespace modules\classifieds\lib\Actions;

class ClassifiedsActionsFactory implements \core\IService
{
	public function createActivateListingByUserAction($listing)
	{
		$action = new ActivateListingByUserAction();
		$action->setListing($listing);
		return $action;
	}

	public function createDisplayOptionsAppliedMessageAction($logger, $listing, $url)
	{
		$action = new DisplayOptionsAppliedMessageAction();
		$action->setLogger($logger);
		$action->setListing($listing);
		$action->setRedirectUrl($url);
		return $action;
	}

	public function createSelectListingOptionsProcess($context, $actions)
	{
		$process = new SelectListingOptionsProcess();
		$process->setContext($context);
		$process->setActions($actions);
		return $process;
	}

	public function createAssignPackageAction($chosenPackageSid, $listing, $contract)
	{
		$action = new AssignPackageAction();
		$action->setChosenPackageSid($chosenPackageSid);
		$action->setListing($listing);
		$action->setContract($contract);
		return $action;
	}

	public function createDisplayChoosePackageAction($contract, $listing, $predefinedRequestData)
	{
		$action = new DisplayChoosePackageAction();
		$action->setContract($contract);
		$action->setListing($listing);
		$action->setPredefinedRequestData($predefinedRequestData);
		return $action;
	}

	public function createDisplayNoActiveContractMessageAction()
	{
		$action = new DisplayNoActiveContractMessageAction();
		return $action;
	}

	public function createActivateOptionsAction($listing, $optionIds)
	{
		$action = new ActivateOptionsAction();
		$action->setListing($listing);
		$action->setOptionIds($optionIds);
		return $action;
	}
}
