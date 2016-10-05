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


namespace modules\basket\apps\FrontEnd;

class TopMenuItem extends \modules\menu\apps\FrontEnd\TopMenuItem
{
    private $listingAmount;
    public function __construct()
    {
        $this->listingAmount = \App()->BasketItemManager->getListingAmountInBasket();
    }

	public function getCaption()
	{
		return "My Basket";
	}

    public function getTitle()
    {
        return ($this->listingAmount > 0) ? "" : "Your basket is empty";
    }

	public function getUrl()
	{
		return \App()->PageRoute->getPagePathById('basket');
	}

	public static function getOrder()
	{
		return 1000;
	}

    public function fetch($params, $templateProcessor)
    {
		if ($this->listingAmount == 0) return;
        $params['listingAmount'] = $this->listingAmount;
        $params['template'] = 'basket^top_menu_item.tpl';
        return parent::fetch($params, $templateProcessor);
    }
}
