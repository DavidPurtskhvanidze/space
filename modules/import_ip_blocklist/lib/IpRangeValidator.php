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

class IpRangeValidator
{
	private $errors = array();

	/**
	 * @param \modules\ip_blocklist\lib\IpRange $ipRange
	 * @return bool
	 */
	function isValid($ipRange)
	{
		$this->errors = array();
		foreach ($ipRange->getProperties() as $property)
		{
			if (!$property->isValid())
			{
				$this->errors[] = $property->getValidationErrorMessage();
			}
		}
		return empty($this->errors);
	}

	public function getErrors()
	{
		return $this->errors;
	}
}
