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
 * After listing feature deactivated action interface.
 * 
 * Interface designed for performing action after listing feature deactivated
 * 
 * @category ExtensionPiont
 */
interface IAfterListingFeatureDeactivated
{
	/**
	 * Feature name setter
	 * @var String $featureId
	 */
	public function setFeatureId($featureId);
	/**
	 * Listing object setter
	 * @param \modules\classifieds\lib\Listing\Listing $listing
	 */
	public function setListing($listing);
	/**
	 * Action executer
	 */
	public function perform();
}
