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

class ExportIpBlocklistDataConverter implements \lib\DataTransceiver\IDataConverter
{
	var $_fieldsScheme;
	private $_ipRangeManager;

	public function setIpRangeManager($ipRangeManager)
	{
		$this->_ipRangeManager = $ipRangeManager;
	}

	function setFieldsScheme($fieldsScheme)
	{
		$this->_fieldsScheme = $fieldsScheme;
	}

	function getConverted($ipRange)
	{
		$this->addIpRangeProperties($ipRange);
		$converted = array_map(array(&$ipRange, 'getPropertyDisplayValue'), $this->_fieldsScheme);
		return $converted;
	}
	
	function addIpRangeProperties(&$ipRange)
	{
		$ipRangeInfo = $this->_ipRangeManager->getIpRangeInfoBySID($ipRange->getSID());
		$registration_date = $ipRangeInfo['added'];

	    $ipRange->addProperty(array(
							'id'	=> 'id',
							'type'	=> 'string',
							'value'	=> $ipRange->getSID(),
							));
		$ipRange->addProperty(array(
							'id'	=> 'added',
							'type'	=> 'string',
							'value'	=> $registration_date,
							));
		
	}
}
