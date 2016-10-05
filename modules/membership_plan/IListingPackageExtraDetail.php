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
 * Membership plans listing package extra detail property provider.
 * 
 * Interface designed for providing additional property for membership plans listing package.
 * Like price for additional listing feature or any other property that we may need in future.
 * 
 * @category ExtensionPiont
 */
interface IListingPackageExtraDetail
{
	/**
	 * Returns property ID
	 * @return string
	 */
	public function getId();
	/**
	 * Returns property caption(human readable)
	 * @return string
	 */
	public function getCaption();
	/**
	 * Returns property type(string, boolean, float, integer, etc..)
	 * @return string
	 */
	public function getType();
	/**
	 * Returns extra property data.
	 * ex: array('length' => '20', 'is_required' => false, 'is_system' => true)
	 * @return array
	 */
	public function getExtraInfo();

	public static function getOrder();
}
