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
 * File reader
 * 
 * Interface designed for implementing file based datasources
 */
interface FileReader
{
	/**
	 * Returns next record
	 * @return mixed
	 */
	public function getNext();
	/**
	 * Return boolean false if there is no more records. Boolean true otherwise
	 * @return bool
	 */
	public function isEmpty();
}
