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

class DeleteBasketItemsAction
{
	private $itemSids = array();

	public function perform()
	{
		\App()->BasketItemManager->deleteItemBySids($this->itemSids);
	}

	public function setItemSids($itemSids)
	{
		$this->itemSids = $itemSids;
	}
}
