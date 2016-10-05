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

use apps\AdminPanel\ContentHandlerBase;
use core\ExtensionPoint;
use lib\Http\RedirectException;

class ListingActionsHandler extends ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'listing_actions';

	public function respond()
	{
		$this->mapActionToMethod
		(
			[
				'ACTIVATE' => [$this, 'activateListing'],
				'DEACTIVATE' => [$this, 'deactivateListing'],
				'DELETE' => [$this, 'deleteListing'],
				'REJECT' => [$this, 'rejectListings'],
				'EDIT PACKAGES' => [$this, 'editPackages'],
				'ASSIGN PACKAGE' => [$this, 'assignPackage'],
            ]
		);

		if (!empty($_REQUEST['returnBackUri']))
		{
			throw new RedirectException(\App()->SystemSettings['SiteUrl'] . $_REQUEST['returnBackUri']);
		}

		if (!empty($_REQUEST['searchId']))
			throw new RedirectException(\App()->PageRoute->getPagePathById('manage_listings') . '?action=restore&searchId=' . $_REQUEST['searchId']);
		$templateProcessor = \App()->getTemplateProcessor();
		
		$listingsIds = array_keys($_REQUEST['listings']);
		$templateProcessor->assign('listingId', array_pop($listingsIds));
		$templateProcessor->assign('action', $_REQUEST['action']);
		$templateProcessor->display('listing_actions.tpl');
	}

	private function activateListing($listingIds)
	{
		foreach ($listingIds as $listingId)
		{
			\App()->ListingManager->approveListingBySID($listingId);

			$afterActivateListingActions = new ExtensionPoint('modules\classifieds\apps\AdminPanel\IAfterActivateListingAction');
			foreach ($afterActivateListingActions as $afterActivateListingAction)
			{
				$afterActivateListingAction->setListingSid($listingId);
				$afterActivateListingAction->perform();
			}
		}
		\App()->SuccessMessages->addMessage('LISTINGS_ACTIVATED', ['count' => sizeof($listingIds), 'listingIds' => $listingIds]);
	}

	private function deactivateListing($listingIds)
	{
		$canPerform = true;
		$validators = new ExtensionPoint('modules\classifieds\apps\AdminPanel\IDeactivateListingsValidator');
		foreach ($validators as $validator)
		{
			$validator->setListingIds($listingIds);
			$canPerform &= $validator->isValid();
		}
		if (!$canPerform) return;

		foreach ($listingIds as $listingId)
		{
			\App()->ListingManager->deactivateListingBySID($listingId);
		}

		$afterDeactivateListingAction = new ExtensionPoint('modules\classifieds\lib\Listing\IAfterDeactivateListingsAction');
		foreach($afterDeactivateListingAction as $action)
		{
			$action->setListingsSid($listingIds);
			$action->perform();
		}

		\App()->SuccessMessages->addMessage('LISTINGS_DEACTIVATED', ['count' => sizeof($listingIds), 'listingIds' => $listingIds]);
	}

	private function deleteListing($listingIds)
	{
		$canPerform = true;
		$validators = new ExtensionPoint('modules\classifieds\apps\AdminPanel\IDeleteListingsValidator');
		foreach ($validators as $validator)
		{
			$validator->setListingIds($listingIds);
			$canPerform &= $validator->isValid();
		}
		if (!$canPerform) return;

		foreach ($listingIds as $listingId)
		{
			$eraser = \App()->ObjectMother->createListingEraser($listingId);
			$eraser->perform();
		}
		\App()->SuccessMessages->addMessage('LISTINGS_DELETED', array('count' => sizeof($listingIds), 'listingIds' => $listingIds));
	}

	private function rejectListings($listingIds)
	{
		$canPerform = true;
		$validators = new ExtensionPoint('modules\classifieds\apps\AdminPanel\IRejectListingsValidator');
		foreach ($validators as $validator)
		{
			$validator->setListingIds($listingIds);
			$canPerform &= $validator->isValid();
		}
		if (!$canPerform) return;

		foreach ($listingIds as $listingId)
		{
			\App()->ListingManager->rejectListingBySID($listingId);

			$afterRejectListingActions = new ExtensionPoint('modules\classifieds\apps\AdminPanel\IAfterRejectListingAction');
			foreach ($afterRejectListingActions as $afterRejectListingAction)
			{
				$afterRejectListingAction->setListingSid($listingId);
				$afterRejectListingAction->perform();
			}
		}
	}
		
	private function editPackages($listingSIDs)
	{   
		$pageURL = \App()->PageRoute->getSystemPageURI('membership_plan', 'edit_lisitng_packages') . '?' .  http_build_query(array('listing_sids' => $listingSIDs, 'searchId' => $_REQUEST['searchId']),'','&');		throw new RedirectException(\App()->SystemSettings['SiteUrl'] . $pageURL);
    }
    
    private function assignPackage($listingSIDs)
	{
        $packageSID = $_REQUEST['package_sid'];
       
        if(!empty($packageSID))
        {
            foreach($listingSIDs as $listingSID)
            {
                $packageInfo = \App()->PackageManager->getPackageInfoBySID($packageSID);
                \App()->ListingPackageManager->updatePackage($listingSID, $packageInfo);
            }
        }
	}

	private function mapActionToMethod($map)
	{
		if (!isset($_REQUEST['action'], $_REQUEST['listings'])) return;
		$action = strtoupper($_REQUEST['action']);
		$listingSids = array_keys($_REQUEST['listings']);
		if (isset($map[$action]))
		{
			call_user_func($map[$action], $listingSids);
		}
	}
}
