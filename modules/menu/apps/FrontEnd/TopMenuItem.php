<?php
/**
 *
 *    Module: menu v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: menu-7.5.0-1
 *    Tag: tags/7.5.0-1@19799, 2016-06-17 13:20:07
 *
 *    This file is part of the 'menu' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\menu\apps\FrontEnd;

abstract class TopMenuItem extends \modules\menu\apps\FrontEnd\MenuItem implements \modules\menu\apps\FrontEnd\ITopMenuItem
{
	protected $params;
	
	public static function getOrder()
	{
		return 100;
	}

	protected function getTemplate()
	{
		return $this->getParamValue('template', 'top_menu_item.tpl');
	}
}
