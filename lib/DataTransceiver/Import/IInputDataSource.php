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


namespace lib\DataTransceiver\Import;

/**
 * Import input data source
 * 
 * Interface designed for implementing input datasources for data import
 */
interface IInputDataSource extends \lib\DataTransceiver\IInputDatasource
{
	/**
	 * Import configuration setter
	 * @param \lib\DataTransceiver\Import\IImportConfig $cofig
	 */
	public function setConfig($config);
	/**
	 * Initializer 
	 */
	public function init();
	/**
	 * Return human readable cation - name of input data source
	 * @return string
	 */
	public function getCaption();
}
