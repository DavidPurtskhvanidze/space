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
 * Import configuration set
 * 
 * Interface designed for implementing import configuration sets. Configuration set keeps information about input data 
 * source, local temporary file names, input data source formats ans so on.
 */
interface IImportConfig
{
	/**
	 * Returns user specified input datasource file location
	 * @return string
	 */
	public function getFilePath();
	/**
	 * Returns local temporary file name to save datasource.
	 * @return string
	 */
	public function getLocalFileName();
	/**
	 * Returns input datasource format
	 * @return string
	 */
	public function getImportFormat();
	/**
	 * Returns additional configuration set specific data.
	 * @name string @name Extra data variable name
	 * @return mixed
	 */
	public function getExtraDataValue($name);
}
