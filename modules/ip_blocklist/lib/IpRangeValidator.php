<?php
/**
 *
 *    Module: ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19789, 2016-06-17 13:19:41
 *
 *    This file is part of the 'ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\ip_blocklist\lib;

class IpRangeValidator extends \lib\ORM\ObjectPropertyValueValidator
{
	protected $errorTemplateModule = "ip_blocklist";

	public function isValid($value, $propertyId, $object)
	{
		try
		{
			$ipProcessor = new IpProcessor($value);
			if ($ipProcessor->isInRange(IpProcessor::getClientIpAsString()))
			{
				throw new Exception('CAN_NOT_BLOCK_LOCAL_IP');
			}

			$existingSid = \App()->IpRangeManager->getIpSidByInfo($ipProcessor->getIpRangeStartAsInt(), $ipProcessor->getIpRangeEndAsInt(), $ipProcessor->getIpRangeMaskAsCidr());

			if (!is_null($existingSid) && $existingSid != $object->getSID())
			{
				throw new Exception('IP_RANGE_ALREADY_EXISTS');
			}
			return true;

		}
		catch (\modules\ip_blocklist\lib\Exception $e)
		{
			$this->errorCode = $e->getMessage();
			return false;
		}
	}
}
