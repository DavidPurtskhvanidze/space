<?php
/**
 *
 *    Module: site_pages v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: site_pages-7.5.0-1
 *    Tag: tags/7.5.0-1@19834, 2016-06-17 13:21:53
 *
 *    This file is part of the 'site_pages' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\site_pages\lib;

/**
 * Site page mass action execute validator
 * 
 * Check if it's allowed to execute mass action. If it returns true than action perform is allowed.
 * 
 * @category ExtensionPoint
 */
interface ISitePageMassActionExecuteValidator
{
	/**
	 * Setter of the action object
	 * @param ISitePageMassAction $action
	 */
	public function setAction($action);
	/**
	 * Setter of application id
	 * @param string $applicationId
	 */
	public function setApplicationId($applicationId);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
