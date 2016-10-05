<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib;

class ImportListDataConfigRequestData implements \lib\DataTransceiver\Import\IImportConfig
{
	private $filePath;

	public function __construct()
	{
		$this->extraData = array
		(
			'csvFileDelimiter' => 'none',
			'skipDelimiterPresenceCheck' => true
		);
	}
	
	public function getFieldSid()
	{
		return \App()->Request['field_sid'];
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
		return 'importListDataSource';
	}

	public function setFilePath($filePath)
	{
		$this->filePath = $filePath;
	}
}
