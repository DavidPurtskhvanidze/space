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
 * Module template data provider.
 *
 * Interface designed for providing data about module templates for modification or any other actions
 * on modules templates from admin panels edit template functionality.
 *
 * @category ExtensionPiont
 */
interface IModuleTemplateProvider
{
	/**
	 * Returns human readable module template provider name.
	 * @return string
	 */
	public function getModuleTemplateProviderName();
	/**
	 * Returns human readable module template provider description.
	 * @return string
	 */
	public function getModuleTemplateProviderDescription();
	/**
	 * Returns modules directory name.
	 * @return string
	 */
	public function getModuleName();
	/**
	 * Returns unique module provider id - class name including namespace.
	 * @return string
	 */
	public function getId();

	public function getAppIds();
}
