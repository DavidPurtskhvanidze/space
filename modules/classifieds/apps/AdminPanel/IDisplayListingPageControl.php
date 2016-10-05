<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\apps\AdminPanel;

/**
 * 'Display Listing' page control
 * 
 * A control that appears on the 'Display Listing' page. The output should be enclosed by '<li></li>' tag!
 * 
 * @category ExtensionPiont
 */
interface IDisplayListingPageControl
{
	/**
 	* Returns control order.
	 * @return integer
	 */
	public static function getOrder();
	/**
 	* Listing sid setter
	 * @return integer
	 */
	public function setListingSid($listingSid);
	/**
   	* Return back uri setter. Can be used to return back after action
 	* @return string
	 */
	public function setReturnBackUri($returnBackUri);
	/**
 	* Displays the content of control. Don't forget <li> tags
	 */
	public function display();
}
