<?php
/**
 *
 *    Module: basket v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: basket-7.5.0-1
 *    Tag: tags/7.5.0-1@19771, 2016-06-17 13:18:56
 *
 *    This file is part of the 'basket' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\basket\lib\Actions;

class BuyItemSuccessActionHelper
{
	/**
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;
	private $boughtOptions = array();

	public function isListingActive()
	{
		return $this->listing->isActive();
	}

	public function isListingActivationBought()
	{
		return in_array('activation', $this->boughtOptions);
	}

	public function activateBoughtOptions()
	{
		$action = \App()->ClassifiedsActionsFactory->createActivateOptionsAction($this->listing, array_diff($this->boughtOptions, array('activation')));
		$action->perform();
	}

	public function activateListingByUser()
	{
		$activateListingByUserAction = \App()->ClassifiedsActionsFactory->createActivateListingByUserAction($this->listing);
		$activateListingByUserAction->perform();
	}

	public function addBoughtOptionsToContainer()
	{
		$addOptionsToContainerAction = \App()->BasketActionsFactory->createAddOptionsToContainerAction($this->listing->getSID(), array_diff($this->boughtOptions, array('activation')));
		$addOptionsToContainerAction->perform();
	}

	public function setListing($listing)
	{
		$this->listing = $listing;
	}

	public function setBoughtOptions($boughtOptions)
	{
		$this->boughtOptions = $boughtOptions;
	}
}
