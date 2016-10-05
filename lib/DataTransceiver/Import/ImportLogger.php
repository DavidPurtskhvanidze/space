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

class ImportLogger implements \lib\DataTransceiver\IDataTransceiverLogger
{
	var $recordNumber = 0;
	var $numberOfImportedRecords = 0;
	var $numberOfInvalidRecords = 0;
	
	var $errors = array();

	function next()
	{
		$this->recordNumber++;
	}
	
	function logSuccess()
	{
		$this->numberOfImportedRecords++;
		$this->next();
	}
	
	function logError($errorsText)
	{
		$this->numberOfInvalidRecords++;
		$this->errors[] = array
		(
			'recordNumber' => $this->recordNumber,
			'line' => $this->recordNumber + 1,
			'errorsText' => $errorsText
		);
		$this->next();
	}

	function logWarning($warningsText)
	{
		$this->errors[] = array
		(
			'recordNumber' => $this->recordNumber,
			'line' => $this->recordNumber + 1,
			'errorsText' => $warningsText,
		);
	}

	public function logTreeValueAdd($amount = 1)
	{
	}
	public function logListValueAdd($amount = 1)
	{
	}

	function getLog()
	{
		return array
		(
			'numberOfImportedRecords' => $this->numberOfImportedRecords,
			'numberOfInvalidRecords' => $this->numberOfInvalidRecords,
			'recordsNumber' => $this->recordNumber,
			'errors' => $this->errors,
		);
	}
}
