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


namespace modules\classifieds;

/**
 * Interface for providing additional options for active listings.
 * 
 * Interface designed for providing additional options for listings. Option will be selectable in section "Manage Listing Options".
 * 
 * @category ExtensionPiont
 */
interface IAdditionalListingOption
{
	/**
	 * Setter of Listing
	 * @param lib\Listing\Listing $listing
	 */
	public function setListing($listing);
	/**
	 * Flag if current option is available for listing
	 * @return bool
	 */
	public function isAvailable();
	/**
	 * Returns price of the option 
	 * @return float price of the option 
	 */
	public function getPrice();
	/**
	 * Activates option
	 */
	public function activateOption();
	/**
	 * Returns option id
	 * @return string
	 */
	public function getId();
	/**
	 * Returns human readable option name
	 * @return string
	 */
	public function getCaption();
	/**
	 * Return additional human readable information
	 * @return string
	 */
	public function getDescription();
	/**
	 * Returns additional html or scripting tags necessary for current option control or functionality
	 * @return string
	 */
	public function getAdditionalScript();
}
