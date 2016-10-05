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


namespace modules\miscellaneous;

/**
 * System settings group renderer interface.
 * 
 * Interface designed for rendering a group of system settins.
 * 
 * @category ExtensionPoint
 */
interface ISystemSettingPage
{
	/**
	 * Returns unique id of group .
	 * @return string
	 */
	public function getId();
	/**
	 * Returns group caption(human readable).
	 */
	public function getCaption();
	/**
	 * Returns HTML code of groups setting entities such as labels, values, input boxes.
	 * @return string
	 */
	public function getContent();

    public static function getOrder();
}
