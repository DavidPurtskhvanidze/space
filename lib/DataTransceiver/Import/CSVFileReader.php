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

class CSVFileReader implements FileReader
{
	private $fileHandler;
	private $delimiter;

	public function __construct($filePath, $delimiter, $skipDelimiterPresenceCheck = false)
	{
		if (!is_file($filePath) || !is_readable($filePath))
			throw new \lib\DataTransceiver\TransceiveFailedException('INVALID_FILE');
		$this->delimiter = $this->parseDelimiter($delimiter);
		if (!$skipDelimiterPresenceCheck)
		{
			$this->validateDelimiter($filePath);
		}
		$this->fileHandler = fopen($filePath, 'r');
	}

	public function getNext()
	{
		return fgetcsv($this->fileHandler, 1024 * 8, $this->delimiter);
	}

	public function isEmpty()
	{
		return feof($this->fileHandler);
	}

	public function __destruct()
	{
		if (is_resource($this->fileHandler)) fclose($this->fileHandler);
	}

	private function validateDelimiter($filePath)
	{
		$fileResource = fopen($filePath, "r");
		$contents = fgets($fileResource, 1024 * 8);
		fclose($fileResource);
		$isDelimiterExists = (bool) preg_match('/' . $this->delimiter . '/', $contents);
		if (!$isDelimiterExists)
			throw new \lib\DataTransceiver\TransceiveFailedException('INVALID_CSV_DELIMITER');
	}
	private function parseDelimiter($delimiterId)
	{
		return \App()->ObjectMother->getDelimiterById($delimiterId);
	}
}
