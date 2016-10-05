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

class MyBasketHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'My Basket';
	protected $moduleName = 'basket';
	protected $functionName = 'my_basket';

	public function respond()
	{
		try
		{
			$templateProcessor = \App()->getTemplateProcessor();
			$basketItems = \App()->BasketItemManager->getItemsByRequestGroupedByListing(\App()->Request->getRequest());

            //Is link to My Basket required?
            $listingAmountInFilteredBasket = count($basketItems);
            $totalListingAmountInBasket = \App()->BasketItemManager->getListingAmountInBasket();
            $displayLinkToMyBasket = false;
            if(!empty(\App()->Request['listing_sid']['equal']) && ($listingAmountInFilteredBasket == 1) && ($totalListingAmountInBasket > 1))
            {
                $displayLinkToMyBasket = true;
            }
            $templateProcessor->assign('displayLinkToMyBasket', $displayLinkToMyBasket);
            $templateProcessor->assign('itemsGroupedByListings', $basketItems);
            $templateProcessor->assign('listingSID', \App()->Request['listing_sid']['equal']);
			if (!empty($_REQUEST))
			{
				$templateProcessor->assign('return_uri', \App()->SystemSettings['SiteUrl'] . \App()->PageManager->getPageUri() . '?' . http_build_query($_REQUEST));
			}
			$templateProcessor->display('my_basket.tpl');
		}
		catch(\modules\basket\lib\Exception $e)
		{
			$requestData = array(		
				'HTTP_REFERER' => \App()->PageRoute->getPagePathById('basket'),
			);
			$url = \App()->PageRoute->getPagePathById('user_login') . '?' . http_build_query($requestData);

			\App()->ErrorMessages->addMessage($e->getId(), array(), $e->getModuleName());
			throw new \lib\Http\RedirectException($url);
		}
	}
}
