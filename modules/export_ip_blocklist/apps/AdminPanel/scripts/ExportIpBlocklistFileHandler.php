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


namespace modules\export_ip_blocklist\apps\AdminPanel\scripts;

class ExportIpBlocklistFileHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'export_ip_blocklist';
	protected $functionName = 'export_blocklist_file';
	protected $rawOutput = true;

	public function respond()
	{
		$allIpRangeSIDs = \App()->Session->getContainer('EXPORT_IP_BLOCKLIST')->getValue('sids');
		$properties = \App()->Session->getContainer('EXPORT_IP_BLOCKLIST')->getValue('properties');

		$exportIpBlocklistFactory = new \modules\export_ip_blocklist\lib\ExportIpBlocklistFactory();
		$exportIpRanges = $exportIpBlocklistFactory->createDataTransceiver($allIpRangeSIDs, $properties);

		$exportIpRanges->perform();
		$exportIpRanges->finalize();
	}
}
