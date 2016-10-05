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

use apps\FrontEnd\ContentHandlerBase;
use core\ExtensionPoint;
use lib\Http\RedirectException;

class MyListingsHandler extends ContentHandlerBase
{
	protected $displayName = 'My Listings';
	protected $moduleName = 'classifieds';
	protected $functionName = 'my_listings';
	protected $parameters = ['category_id'];

	private $additionalQuery = "";

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

		if (!is_null(\App()->Request['listings']))
		{
			$listingsSids = array_keys($_REQUEST['listings']);

			if (!is_null(\App()->Request['action_deactivate']))
			{
				$this->deactivateListings($listingsSids);
			}
			elseif (!is_null(\App()->Request['action_delete']))
			{
				$this->deleteListings($listingsSids);
			}

			throw new RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?action=restore&searchId={$_REQUEST['searchId']}{$this->additionalQuery}");
		}

		$current_user_sid = \App()->UserManager->getCurrentUserSID();
		$_REQUEST['user_sid'] = ['equal' => $current_user_sid];
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
		$template_processor->assign('featureActivatedForListing', isset($_REQUEST['featureActivatedForListing']) ? $_REQUEST['featureActivatedForListing'] : null);
		$template_processor->assign('featureActivated', isset($_REQUEST['featureActivated']) ? $_REQUEST['featureActivated'] : null);
		$template_processor->assign('userTotalListingNumber', $userTotalListingNumber);
		$template_processor->assign('listingsInBasket', \App()->BasketItemManager->getListingSidsInBasketByUserSid($current_user_sid));
		$template_processor->display('my_listings_main.tpl');
	}

	private function deleteListings($listingSids)
	{
		$canPerform = true;
		$validators = new ExtensionPoint('modules\classifieds\apps\FrontEnd\IDeleteListingsValidator');
		foreach ($validators as $validator)
		{
			$validator->setListingSids($listingSids);
			$canPerform &= $validator->isValid();
		}
		if (!$canPerform) return;

		$count = 0;
		foreach ($listingSids as $listingSid)
		{
			$canPerform = true;
			$validators = new ExtensionPoint('modules\classifieds\apps\FrontEnd\IDeleteListingValidator');
			foreach ($validators as $validator)
			{
				$validator->setListingSid($listingSid);
				$canPerform &= $validator->isValid();
			}
			if ($canPerform)
			{
				\App()->ObjectMother->createListingEraser($listingSid)->perform();
				$count++;
			}
		}
		\App()->SuccessMessages->addMessage('LISTINGS_DELETED', ['count' => $count]);
	}

	private function deactivateListings($listingSids)
	{
		$canPerform = true;
		$validators = new ExtensionPoint('modules\classifieds\apps\FrontEnd\IDeactivateListingsValidator');
		foreach ($validators as $validator)
		{
			$validator->setListingSids($listingSids);
			$canPerform &= $validator->isValid();
		}
		if (!$canPerform) return;

		$count = 0;
		foreach ($listingSids as $listingSid)
		{
			$canPerform = true;
			$validators = new ExtensionPoint('modules\classifieds\apps\FrontEnd\IDeactivateListingValidator');
			foreach ($validators as $validator)
			{
				$validator->setListingSid($listingSid);
				$canPerform &= $validator->isValid();
			}
			if ($canPerform)
			{
				\App()->ListingManager->deactivateListingBySID($listingSid);
				$count++;
				$lastListingSid = $listingSid;
			}
		}

        $afterDeactivateListingAction = new ExtensionPoint('modules\classifieds\lib\Listing\IAfterDeactivateListingsAction');
        foreach($afterDeactivateListingAction as $action)
        {
            $action->setListingsSid($listingSids);
            $action->perform();
        }

		if ($count == 1)
		{
			$this->additionalQuery .= "&listingDeactivated=$lastListingSid#listing$lastListingSid";
		}
		elseif ($count > 0)
		{
			\App()->SuccessMessages->addMessage('LISTINGS_DEACTIVATED', ['count' => $count]);
		}
	}
}
