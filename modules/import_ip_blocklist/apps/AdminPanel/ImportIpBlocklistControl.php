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


namespace modules\import_ip_blocklist\apps\AdminPanel;

class ImportIpBlocklistControl implements \modules\ip_blocklist\apps\AdminPanel\IBlocklistControl
{
	public function getControlInfo()
	{
		return array(
			'caption' => 'Import IP list',
			'url' => \App()->PageRoute->getSystemPageURI('import_ip_blocklist', 'import_blocklist'),
			'absoluteUrl' => false,
		);
	}
	
	public static function getOrder()
	{
		return 200;
	}
}
