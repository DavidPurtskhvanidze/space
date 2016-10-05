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


namespace modules\menu\apps\AdminPanel;

/**
 * Menu items group interface
 * 
 * Interface designed for providing data for menu(Admin panel) items group.
 * 
 * @category ExtensionPiont
 */
interface IMenuBlock
{
	/**
	 * Returns caption of the menu items group
	 * @return string
	 */
	public function getCaption();
	/**
	 * Returns menu items group order.
	 * @return integer
	 */
	public static function getOrder();
	/**
	 * Returns array of menu item data. Where menu item id array consist of 'title', 'reference', 'highlight' keys.
	 */
	public function getItems();
}
