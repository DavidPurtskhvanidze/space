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


namespace modules\classifieds\apps\FrontEnd;

/**
 * Deactivate listings validator
 * 
 * Interface designed for validating deactivate listings action in FrontEnd. If it returns false, listings will not be deactivated.
 * 
 * @category ExtensionPoint
 */
interface IDeactivateListingsValidator
{
	/**
	 * Setter of listing sids
	 * @param array $listingSids
	 */
	public function setListingSids($listingSids);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
