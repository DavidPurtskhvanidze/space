<?php
/**
 *
 *    Module: membership_plan v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: membership_plan-7.5.0-1
 *    Tag: tags/7.5.0-1@19798, 2016-06-17 13:20:05
 *
 *    This file is part of the 'membership_plan' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\membership_plan;

/**
 * Listing feature interface
 * 
 * Interface designed for providing additional feature and feature controls for listings.
 * 
 * @category ExtensionPiont
 */
interface IListingFeature
{
	/**
	 * Returns feature name(human readable)
	 * @return string
	 */
	public function getFeatureName();

	/**
	 * Returns id of listing package property which value is price of listing feature
	 * @return string
	 */
	public function getPricePropertyId();

	/**
	 * Returns id of the listing package property which defines if listing feature allowed or not
	 * @return string
	 */
	public function getAllowedPropertyId();

	/**
	 * Returns id of listing property which value defines if listing feature is activated
	 * @return string
	 */
	public function getListingPropertyId();

	/**
	 * Returns caption(human readable) of listing property which value defines if listing feature is activated
	 * @return string
	 */
	public function getListingPropertyCaption();

	/**
	 * Returns caption of control which activates listing feature
	 * @return string
	 */
	public function getActivationControlCaption();
}
