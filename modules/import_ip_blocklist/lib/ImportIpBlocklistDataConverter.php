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

class ImportIpBlocklistDataConverter implements \lib\DataTransceiver\IDataConverter
{
	var $ipRangeManager;
	var $arrayCombiner;
	var $fieldsScheme;
	var $ipRangeRequiredFieldsDefiner;
	var $i18n;

	function setIpRangeRequiredFieldsDefiner($ipRangeRequiredFieldsDefiner)
	{
		$this->ipRangeRequiredFieldsDefiner = $ipRangeRequiredFieldsDefiner;
	}
	
	function setFieldsScheme($fieldsScheme)
	{
		$this->fieldsScheme = $fieldsScheme;
	}

	function setArrayCombiner($arrayCombiner)
	{
		$this->arrayCombiner = $arrayCombiner;
	}

	function setIpRangeManager($ipRangeManager)
	{
		$this->ipRangeManager = $ipRangeManager;
	}
	function setI18N($i18n)
	{
		$this->i18n = $i18n;
	}

	function getConverted($ipRangeInfo)
	{
		$ipRangeInfo = $this->arrayCombiner->combine($this->fieldsScheme, $ipRangeInfo);
		$ipRange = $this->ipRangeManager->createObjectForImport($ipRangeInfo);
		$this->ipRangeRequiredFieldsDefiner->process($ipRange);
		return $ipRange;
	}
}
