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

class ImportIpBlocklistOutputDatasource implements \lib\DataTransceiver\IOutputDatasource
{
	/**
	 * @var \modules\ip_blocklist\lib\IpRangeManager
	 */
	var $ipRangeManager;
	/**
	 * @var IpRangeValidator
	 */
	var $ipRangeValidator;

	function setIpRangeValidator($ipRangeValidator)
	{
		$this->ipRangeValidator = $ipRangeValidator;
	}
	
	function setIpRangeManager($ipRangeManager)
	{
		$this->ipRangeManager = $ipRangeManager;
	}

	function add($ipRange)
	{
		$this->ipRangeManager->saveIpRange($ipRange);
	}

	function canAdd($ipRange)
	{
		return $this->ipRangeValidator->isValid($ipRange);
	}

	public function getErrors()
	{
		return $this->ipRangeValidator->getErrors();
	}

	public function finalize()
	{
	}
}
