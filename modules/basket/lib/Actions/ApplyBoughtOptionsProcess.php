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

class ApplyBoughtOptionsProcess
{
	private $optionsData = array();

	public function perform()
	{
		$deletedListingSids = array();
		foreach ($this->optionsData as $listingSid => $options)
		{
			$listing = \App()->ListingManager->getObjectBySID($listingSid);
			if ($listing)
			{
				$action = \App()->BasketActionsFactory->createBuyItemSuccessAction($listing, $options);
				$action->perform();
			}
			else
			{
				$deletedListingSids[] = $listingSid;
			}
		}
		
		if (!empty($deletedListingSids))
		{
			\App()->WarningMessages->addMessage('LISTINGS_HAS_BEEN_DELETED', array('listing_sids' => $deletedListingSids), 'basket');
		}
	}

	/**
	 * @param array $optionsData array($listingSid1 => array($optionId1, $optionId2), ...);
	 */
	public function setOptionsData($optionsData)
	{
		$this->optionsData = $optionsData;
	}
}
