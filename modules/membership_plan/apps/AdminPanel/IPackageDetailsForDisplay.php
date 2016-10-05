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


namespace modules\membership_plan\apps\AdminPanel;

/**
 * On display Membership Plan/Contract package detals action interface.
 * 
 * Interface designed for providing additional properties for that will be used for displaying Membership Plan/Contract
 * plan package detals.
 * 
 * @category ExtensionPiont
 */
interface IPackageDetailsForDisplay
{
	/**
	 * Returns menu items group order.
	 * @return integer
	 */
	public static function getOrder();
	/**
	 * Package object setter
	 * @param \modules\membership_plan\lib\Package\Package $package
	 */
	public function setPackage($package);
	/**
	 * Action executer
	 */
	public function perform();
}
