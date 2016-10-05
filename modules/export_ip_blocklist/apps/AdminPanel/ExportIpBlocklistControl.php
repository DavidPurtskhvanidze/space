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


namespace modules\export_ip_blocklist\apps\AdminPanel;

class ExportIpBlocklistControl implements \modules\ip_blocklist\apps\AdminPanel\IBlocklistControl
{
	public function getControlInfo()
	{
		return array(
			'caption' => 'Export IP list',
			'url' => \App()->PageRoute->getSystemPageURI('export_ip_blocklist', 'export_blocklist'),
			'absoluteUrl' => false,
		);
	}
	
	public static function getOrder()
	{
		return 300;
	}
}
