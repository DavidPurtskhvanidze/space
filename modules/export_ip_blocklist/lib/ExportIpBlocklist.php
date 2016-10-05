<?php
/**
 *
 *    Module: export_ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19778, 2016-06-17 13:19:13
 *
 *    This file is part of the 'export_ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_ip_blocklist\lib;

class ExportIpBlocklist
{
	var $ipRangeSids;
	var $fieldsScheme;

	function setIpRangeSids($IpRangeSids)
	{
		$this->ipRangeSids = $IpRangeSids;
	}
	
	function setFieldsScheme($fieldsScheme)
	{
		$this->fieldsScheme = $fieldsScheme;
	}
	
	function getInputDataSource()
	{
		$datasource = new ExportIpBlocklistInputDataSource();
		$datasource->setIpRangeSids($this->ipRangeSids);
		$datasource->setIpRangeManager(\App()->IpRangeManager);
		return $datasource;
	}
	
	function getOutputDataSource()
	{
		$datasource = new ExportIpBlocklistOutputDataSource();
		$datasource->setOutputFileHandler($this->getOutputFileHandler());
		return $datasource;
	}
	
	function getDataConverter()
	{
		$converter = new ExportIpBlocklistDataConverter();
		$converter->setFieldsScheme($this->fieldsScheme);
		$converter->setIpRangeManager(\App()->IpRangeManager);
		return $converter;
	}
	
	function getLogger()
	{
		$logger = new \lib\DataTransceiver\Export\ExportLogger();
		return $logger;
	}
	
	function getValidator()
	{
		$validator = new \lib\DataTransceiver\Export\ExportValidator();
		return $validator;
	}
	
	function getOutputFileHandler()
	{
		$outputFileHandler = new \lib\DataTransceiver\Export\ExportXlsFileHandler();
		$outputFileHandler->setHeadRowData($this->fieldsScheme);
		return $outputFileHandler;
	}
}
