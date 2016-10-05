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

class BuyItemsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'My Basket';
	protected $moduleName = 'basket';
	protected $functionName = 'buy_items';

	/**
	 * User
	 * @var \modules\users\lib\User\User
	 */
	private $currenUser;
	/**
	 * basketItemInfo
	 * @var Array
	 */
	private $basketItemsInfo = array();

	public function respond()
	{
		$this->checkAndInitEnvironment();

		$totalPrice = 0;
		$optionIdsByListing = array();
		foreach ($this->basketItemsInfo as $basketItemInfo)
		{
			$totalPrice += $basketItemInfo['price'];
			$optionIdsByListing[$basketItemInfo['listing_sid']][] = $basketItemInfo['option_id'];
		}

		$basketItemsGroupedByListing = $this->groupByListing($this->basketItemsInfo);
		$listingIds = array_keys($basketItemsGroupedByListing);
		$invoiceInfo = array
		(
			'user_sid' => \App()->UserManager->getCurrentUserSID(),
			'amount' => $totalPrice,
			'product_id' => 'LISTING_OPTIONS',
			'product_description' => 'For listings ' . join(", ", $listingIds),
			'product_info' => $basketItemsGroupedByListing,
			'product_info_template' => 'basket^product_info_listing_options.tpl',
			'payment_queued_action' => \App()->BasketActionsFactory->createDeleteBasketItemsAction(array_keys($this->basketItemsInfo)),
			'success_action' => \App()->BasketActionsFactory->createApplyBoughtOptionsProcess($optionIdsByListing),
			'success_page_url' => \App()->PageRoute->getSystemPageURL('basket', 'payment_success_page'),
		);
		$invoice = App()->InvoiceManager->createNewInvoice($invoiceInfo);
		throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPageURL('payment_system', 'make_payment') . '?invoice_sid=' . $invoice->getSID());
	}
	

	private function groupByListing($basketItemsInfo)
	{
		$result = array();
		foreach ($basketItemsInfo as $itemInfo)
		{
			$result[$itemInfo['listing_sid']][] = $itemInfo;
		}
		return $result;
	}

	private function checkAndInitEnvironment()
	{
		if (!\App()->UserManager->isUserLoggedIn())
		{
			\App()->ErrorMessages->addMessage('NOT_LOGGED_IN');
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('user_login'));
		}

		$basketItemSids = \App()->Request['listing_options'];
		if (is_array($basketItemSids) || !empty($basketItemSids))
		{
			$this->basketItemsInfo = \App()->BasketItemManager->getItemsInfoByRequest(array('sid' => array('in' => $basketItemSids)));
		}
		if (empty($this->basketItemsInfo))
		{
			throw new \lib\Http\NoContent('No items passed to buy');
		}
	}
}
