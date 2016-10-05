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


namespace modules\basket\apps\FrontEnd\scripts;

class RemoveItemHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Remove Listing Options';
	protected $moduleName = 'basket';
	protected $functionName = 'remove_item';

	public function respond()
	{
		$deletingOptionId = null;
		if (!is_null(\App()->Request['sid']))
		{
			$listingSid = \App()->BasketItemManager->getListingSidByOptionSid(\App()->Request['sid']);
			$itemsGroupedByListing = \App()->BasketItemManager->getItemsByRequestGroupedByListing(
				array('listing_sid' => array('equal' => $listingSid))
			);
			$deletingOptionId = \App()->BasketItemManager->getOptionIdByOptionSid(\App()->Request['sid']);
			\App()->BasketItemManager->deleteItemBySid(\App()->Request['sid']);
			$messageQuery['OPTION_REMOVED'] = 1;
		}
		else
		{
			$itemsGroupedByListing = \App()->BasketItemManager->getItemsByRequestGroupedByListing(
				array('listing_sid' => array('equal' => \App()->Request['listing_sid']))
			);
			\App()->BasketItemManager->deleteItemByListingSid(\App()->Request['listing_sid']);
			$messageQuery['LISTING_REMOVED'] = 1;
		}
		
		if (\App()->Request['return_uri'])
		{
			$searchCriterias = array();
			$urlComponents = parse_url(\App()->Request['return_uri']);
			parse_str($urlComponents['query'], $searchCriterias);
			$result = \App()->BasketItemManager->getItemsInfoByRequest($searchCriterias);
			if (empty($result))
			{
				unset(\App()->Request['return_uri']);
				$messageQuery['LISTING_REMOVED'] = 1;
			}
		}

		$itemsGroupedByListing = array_shift($itemsGroupedByListing);
		foreach($messageQuery as $messageId => $dummy)
		{
			$messageData = array(
				'listing' => $itemsGroupedByListing['listing']
			);
			if (!is_null($deletingOptionId))
			{
				$messageData['optionName'] = $itemsGroupedByListing['paidListingPackageOptions'][$deletingOptionId]['caption'];
			}
			\App()->SuccessMessages->addMessage($messageId, $messageData);
		}
		
		$redirectUrl = (\App()->Request['return_uri']) ? \App()->Request['return_uri']
			: \App()->PageRoute->getPagePathById('basket');
		throw new \lib\Http\RedirectException($redirectUrl);
	}
}
