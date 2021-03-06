<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\apps\AdminPanel;

/**
 * Delete all geographic data validator
 * 
 * Interface designed for validating delete all geographic data action in AdminPanel. If it returns false, no geographic data will be deleted.
 * 
 * @category ExtensionPoint
 */
interface IDeleteAllGeographicDataValidator
{
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
