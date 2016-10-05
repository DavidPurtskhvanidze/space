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
 * Edit custom setting validator
 *
 * If returns false, custom setting won't be edited (saved).
 *
 * @category ExtensionPoint
 */
interface IEditCustomSettingValidator
{
	/**
	 * Custom setting sid setter
	 * @param integer $sid
	 */
	public function setSid($sid);
	/**
	 * Custom setting new id setter
	 * @param string $newId
	 */
	public function setNewId($newId);
	/**
	 * Custom setting new value setter
	 * @param string $newValue
	 */
	public function setNewValue($newValue);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
