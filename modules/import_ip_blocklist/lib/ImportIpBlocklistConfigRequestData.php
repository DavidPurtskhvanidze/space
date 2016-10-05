<?php
/**
 *
 *    Module: import_ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19786, 2016-06-17 13:19:33
 *
 *    This file is part of the 'import_ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_ip_blocklist\lib;

class ImportIpBlocklistConfigRequestData implements \lib\DataTransceiver\Import\IImportConfig
{
	private $filePath;

	public function __construct()
	{
		$this->extraData = array
		(
			'csvFileDelimiter' => \App()->Request['csv_delimiter']
		);
	}

	public function getExtraDataValue($name)
	{
		return isset($this->extraData[$name]) ? $this->extraData[$name] : null;
	}

	public function getFilePath()
	{
		return $this->filePath;
	}
	public function getImportFormat()
	{
		return \App()->Request['file_type'];
	}
	public function getLocalFileName()
	{
		return 'importIpBlocklistSource';
	}

	public function setFilePath($filePath)
	{
		$this->filePath = $filePath;
	}
}
