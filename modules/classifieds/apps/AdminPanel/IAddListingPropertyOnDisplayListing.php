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
 * Add listing property on display listing interface
 * 
 * Interface designed for adding additional listing preperties on display listing action.
 * 
 * @category ExtensionPiont
 */
interface IAddListingPropertyOnDisplayListing
{
	/**
	 * Setter of listing
	 * @param \modules\classifieds\lib\Listing\Listing $listing
	 */
	public function setListing($listing);
	/**
	 * Action executer
	 */
	public function perform();
}
