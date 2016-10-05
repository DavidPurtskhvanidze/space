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
 * Data transceiver logger interface
 * 
 * Interface designed for implementing data transceiver logger
 */
interface IDataTransceiverLogger
{
	/**
	 * Increases successfull action counter by one
	 */
	public function logSuccess();
	/**
	 * Increases failed action counter by one and stores error message
	 * @param string $errors
	 */
	public function logError($errors);
	/**
	 * Increases actions with warning counter by one and stores warning message
	 * @param string $warningsText
	 */
	public function logWarning($warningsText);
	/**
	 * Returns array of log data
	 * @return array
	 */
	public function getLog();
}
