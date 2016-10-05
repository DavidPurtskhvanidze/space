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

class BasketActionsFactory implements \core\IService
{
	public function createAddOptionsToBasketAction($listingSid, $userSid, $optionIds)
	{
		$action = new AddOptionsToBasketAction();
		$action->setListingSid($listingSid);
		$action->setOptionIds($optionIds);
		$action->setUserSid($userSid);
		return $action;
	}

	public function createDisplaySelectOptionsAction($listing, $predefinedRequestData)
	{
		$action = new DisplaySelectOptionsAction();
		$action->setListing($listing);
		$action->setPredefinedRequestData($predefinedRequestData);
		return $action;
	}

	public function createAddOptionsToContainerAction($listingSid, $optionIds)
	{
		$action = new AddOptionsToContainerAction();
		$action->setListingSid($listingSid);
		$action->setOptionIds($optionIds);
		return $action;
	}

	public function createAddActivationToBasketAction($listingSid, $userSid)
	{
		$action = new AddActivationToBasketAction();
		$action->setListingSid($listingSid);
		$action->setUserSid($userSid);
		return $action;
	}

	public function createDeleteBasketItemsForListing($listingSid)
	{
		$action = new DeleteBasketItemsForListing();
		$action->setListingSid($listingSid);
		return $action;
	}

	public function createDeleteContainerItemsForListing($listingSid)
	{
		$action = new DeleteContainerItemsForListing();
		$action->setListingSid($listingSid);
		return $action;
	}

	public function createBuyItemSuccessAction($listing, $boughtOptions)
	{
		$helper = new BuyItemSuccessActionHelper();
		$helper->setListing($listing);
		$helper->setBoughtOptions($boughtOptions);

		$action = new BuyItemSuccessAction();
		$action->setHelper($helper);
		return $action;
	}

	public function createDeleteBasketItemsAction($itemSids)
	{
		$action = new DeleteBasketItemsAction();
		$action->setItemSids($itemSids);
		return $action;
	}

	public function createApplyBoughtOptionsProcess($optionsData)
	{
		$process = new ApplyBoughtOptionsProcess();
		$process->setOptionsData($optionsData);
		return $process;
	}
	
	public function createSynchronizeBasketItemsWithPackageAction($packageSid)
	{
		$action = new SynchronizeBasketItemsWithPackageAction();
		$action->setPackageSid($packageSid);
		return $action;
	}
}
