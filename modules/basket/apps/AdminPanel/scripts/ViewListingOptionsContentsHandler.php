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


namespace modules\basket\apps\AdminPanel\scripts;

class ViewListingOptionsContentsHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $displayName = 'View Listing Options Contents';
	protected $moduleName = 'basket';
	protected $functionName = 'view_listing_options_contents';
	protected $rawOutput = true;

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('itemsGroupedByListing', $this->defineObjects(\App()->Request['listing_options']));
		$templateProcessor->assign('payment_method', \App()->Request['payment_method']);
		$templateProcessor->display('listing_options_contents.tpl');
	}

	private function defineObjects($listingOptionsGroupedByListing)
	{
		$res = array();
		foreach ($listingOptionsGroupedByListing as $listingSid => $listingOptions)
		{
			foreach ($listingOptions as $option)
			{
				if (!isset($res[$listingSid]))
				{
					$listing = \App()->ListingManager->getObjectBySID($listingSid);
					$res[$listingSid] = array
					(
						'listing' => !is_null($listing) ? \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($listing) : null,
						'options' => array(),
						'totalOptionPrice' => 0,
					);
				}
				$res[$listingSid]['options'][] = $option;
				$res[$listingSid]['totalOptionPrice'] += $option['price'];
			}
		}
		return $res;

	}
}
