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

class AddOptionsToBasketAction
{
	private $listingSid;
	private $userSid;
	private $optionIds = array();

	public function perform()
	{
		\App()->BasketItemManager->addItemsToBasket($this->listingSid, $this->userSid, $this->optionIds);
	}

	public function setListingSid($listingSid)
	{
		$this->listingSid = $listingSid;
	}

	public function setOptionIds($optionIds)
	{
		$this->optionIds = $optionIds;
	}

	public function setUserSid($userSid)
	{
		$this->userSid = $userSid;
	}
}
