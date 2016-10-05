<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace apps;

/**
 * Visualization theme interface.
 * 
 * Interface designed for implementation visualization themes.
 */
interface ITheme
{
	/**
	 * Returns initialized template object.
	 * @param string $moduleName Requested module name
	 * @param string $templateName Requested template name
	 * @return \modules\smarty_based_template_processor\lib\Template
	 */
	public function getTemplate($moduleName, $templateName);
	/**
	 * Returns uniform resource locator of $fileId of $moduleName
	 * @param string $moduleName Requested module name
	 * @param string $fileId Requested file id
	 * @return string
	 */
	public function getFileUrl($moduleName, $fileId);
	/**
	 * Returns file path of $fileId of $moduleName
	 * @param string $moduleName Requested module name
	 * @param string $fileId Requested file id
	 * @return string
	 */
	public function getFilePath($moduleName, $fileId);
	/**
	 * Returns theme name
	 * @return string
	 */
	public function getName();
	/**
	 * Return current theme's parent name
	 * @return string
	 */
	public function getParentTheme();
	/**
	 * Checks if current theme has parent
	 * @return bool
	 */
	public function hasParentTheme();
}
