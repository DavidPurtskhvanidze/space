<?php
/**
 *
 *    Module: publications v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: publications-7.5.0-1
 *    Tag: tags/7.5.0-1@19806, 2016-06-17 13:20:27
 *
 *    This file is part of the 'publications' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\publications\apps\AdminPanel;

/**
 * Delete publication validator
 * 
 * Interface designed for validating delete publication category action in AdminPanel. If it returns false, category will not be deleted.
 * 
 * @category ExtensionPoint
 */
interface IDeletePublicationCategoryValidator
{
	/**
	 * Setter of publication category id
	 * @param int $categoryId
	 */
	public function setCategoryId($categoryId);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
