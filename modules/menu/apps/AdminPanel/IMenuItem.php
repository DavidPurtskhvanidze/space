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
 * Menu item interface.
 * 
 * Interface designed for providing data for menu(Admin panel) item.
 * 
 * @category ExtensionPiont
 */
interface IMenuItem
{
	/**
	 * Returns menu item caption.
	 * @return string
	 */
	public function getCaption();
	/**
	 * Returns full URL.
	 * @return string
	 */
	public function getUrl();
	/**
	 * Returns array of URLs on which this menu items must be highlighted. 
	 * ex: if this menu utems URL 'URL/manage_phrases/', array('URL/add_phrase/', 'URL/edit_phrase/').
	 * @return array
	 */
	public function getHighlightUrls();
	/**
	 * Returns menu item order.
	 * @return integer
	 */
	public static function getOrder();
}
