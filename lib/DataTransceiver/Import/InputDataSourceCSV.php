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

class InputDataSourceCSV extends InputDataSourceTable
{
	public function init()
	{
		if (($filePath = \App()->FileSystem->downloadFileIfNotExistsOrModified($this->config->getFilePath(), $this->config->getLocalFileName())) === false)
		{
			throw new \lib\DataTransceiver\TransceiveFailedException('INVALID_FILE');
		}

		if ($this->isArchive($filePath))
		{
			$filePath = $this->getUnpackedFilePath($filePath, 'csv');
		}
		elseif (!$this->config->getExtraDataValue('skipFileExtensionCheck') && !in_array(pathinfo($filePath, PATHINFO_EXTENSION), $this->getAllowedFileExtensions()))
		{
			throw new \lib\DataTransceiver\TransceiveFailedException('INVALID_FILE_TYPE');
		}

		$delimiter = $this->config->getExtraDataValue('csvFileDelimiter');
		$skipDelimiterPresenceCheck = $this->config->getExtraDataValue('skipDelimiterPresenceCheck');
		$this->setFileReader(new \lib\DataTransceiver\Import\CSVFileReader($filePath, $delimiter, $skipDelimiterPresenceCheck));
		parent::init();
	}

	public function getCaption()
	{
		return "CSV";
	}

	private function getAllowedFileExtensions()
	{
		return array('csv', 'txt', 'tab');
	}
}
