<?php
/**
 *
 *    Module: content_management v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: content_management-7.3.0-1
 *    Tag: tags/7.3.0-1@18511, 2015-08-24 13:35:46
 *
 *    This file is part of the 'content_management' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\content_management\apps\AdminPanel;

class MenuBlock extends \modules\menu\apps\AdminPanel\MenuBlock
{
	public function getCaption()
	{
		return "Content Management";
	}

	public static function getOrder()
	{
		return 700;
	}

	public function getIMenuItemInterfaceName()
	{
		return 'modules\content_management\apps\AdminPanel\IMenuItem';
	}
}
