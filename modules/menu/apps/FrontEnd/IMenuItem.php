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

/**
 * Interface callable from templates for displaying additional menu items
 *
 * @category ExtensionPoint
 */
interface IMenuItem extends \modules\main\lib\ITemplateProcessorExtensionPoint
{
	/**
	 * Returns menu item caption.
	 * @return string
	 */
	public function getCaption();
	/**
	 * Returns menu item title(hint text).
	 * @return string
	 */
	public function getTitle();
	/**
	 * Returns full URL.
	 * @return string
	 */
	public function getUrl();
	/**
	 * Returns menu item order.
	 * @return integer
	 */
	public static function getOrder();
}
