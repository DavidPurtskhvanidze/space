<?php
/**
 *
 *    Module: template_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: template_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19839, 2016-06-17 13:22:09
 *
 *    This file is part of the 'template_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\template_manager\apps\AdminPanel;

/**
 * Theme action validator
 * 
 * Interface designed for validating action on theme in AdminPanel. If it returns false, theme action is not allowed.
 * 
 * @category ExtensionPoint
 */
interface IThemeActionValidator
{
	/**
	 * action setter 
	 * @param string $action
	 */
	public function setAction($action);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
