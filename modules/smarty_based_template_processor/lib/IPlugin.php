<?php
/**
 *
 *    Module: smarty_based_template_processor v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: smarty_based_template_processor-7.5.0-1
 *    Tag: tags/7.5.0-1@19835, 2016-06-17 13:21:56
 *
 *    This file is part of the 'smarty_based_template_processor' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\smarty_based_template_processor\lib;

/**
 * Smarty plugin registerer interface
 * 
 * Interface designed for registration Smarty plugin to be used in templates
 * 
 * @category ExtensionPiont
 */
interface IPlugin
{
	/**
	 * Returns Smarty plugin type
	 * @return string
	 */
	public function getPluginType();
	/**
	 * Returns name of template tag
	 * @return string
	 */
	public function getPluginTag();
	/**
	 * Returns PHP callback to register
	 * @return string
	 */
	public function getPluginCallback();
}
