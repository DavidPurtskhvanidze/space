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

class AddOptionsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Add Listing Options';
	protected $moduleName = 'basket';
	protected $functionName = 'add_options';
	protected $rawOutput = true;

	public function respond()
	{
		if (\App()->Request['action'] == 'add')
		{
			\App()->BasketItemManager->addItemsToBasket(
				\App()->Request['listing_sid'], 
				\App()->UserManager->getCurrentUserSID(), 
				\App()->Request['option_ids']
			);
			$redirectUrl = (\App()->Request['return_uri']) ? \App()->Request['return_uri']
				: \App()->PageRoute->getPagePathById('basket');
			throw new \lib\Http\RedirectException($redirectUrl);
		}
		else
		{
			$templateProcessor = \App()->getTemplateProcessor();
			$templateProcessor->assign('listing_sid', \App()->Request['listing_sid']);
			$templateProcessor->assign('options', \App()->BasketItemManager->getBuyableOptionsByListingSid(\App()->Request['listing_sid']));
			$templateProcessor->assign('return_uri', \App()->Request['return_uri']);
			$templateProcessor->display('add_options.tpl');
		}
	}
}
