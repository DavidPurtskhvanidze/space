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
 * Edit listing setting validator
 * 
 * Interface designed for validating edit listing action in AdminPanel. If it returns false, listing will not be saved.
 * 
 * @category ExtensionPoint
 */
interface IEditListingValidator
{
	/**
	 * Setter of listing
	 * @param \modules\classifieds\lib\Listing\Listing $listing
	 */
	public function setListing(\modules\classifieds\lib\Listing\Listing $listing);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
