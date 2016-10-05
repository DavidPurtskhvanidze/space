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
 * Delete listing package validator
 * 
 * Interface designed for validating delete listing package action in AdminPanel. If it returns false, listing package will not be deleted.
 * 
 * @category ExtensionPoint
 */
interface IDeleteListingPackageValidator
{
	/**
	 * Setter of listing package sid
	 * @param int $listingPackage
	 */
	public function setListingPackage($listingPackage);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
