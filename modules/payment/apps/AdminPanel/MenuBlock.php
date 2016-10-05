<?php
/**
 *
 *    Module: payment v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: payment-7.5.0-1
 *    Tag: tags/7.5.0-1@19802, 2016-06-17 13:20:16
 *
 *    This file is part of the 'payment' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\payment\apps\AdminPanel;

class MenuBlock extends \modules\menu\apps\AdminPanel\MenuBlock
{
	public function getCaption()
	{
		return "Payment Management";
	}

	public static function getOrder()
	{
		return 500;
	}

	public function getIMenuItemInterfaceName()
	{
		return 'modules\payment\apps\AdminPanel\IMenuItem';
	}
}
