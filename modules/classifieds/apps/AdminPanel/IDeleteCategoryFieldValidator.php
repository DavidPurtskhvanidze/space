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
 * Delete category field validator
 * 
 * Interface designed for validating delete category field action in AdminPanel. If it returns false, category field will not be deleted.
 * 
 * @category ExtensionPoint
 */
interface IDeleteCategoryFieldValidator
{
	/**
	 * Setter of category field sid
	 * @param int $categoryFieldSid
	 */
	public function setCategoryFieldSid($categoryFieldSid);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
