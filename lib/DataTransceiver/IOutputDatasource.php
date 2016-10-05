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


namespace lib\DataTransceiver;

/**
 * Output datasource
 * 
 * Interface designed for implementing export output datasources
 */
interface IOutputDatasource
{
	/**
	 * Adds record data to datasource
	 * @param mixed $data
	 */
	function add($data);
	/**
	 * Returns boolean true if $data is valid and can be added to datasource. Boolean false otherwise
	 */
	function canAdd($data);
}
