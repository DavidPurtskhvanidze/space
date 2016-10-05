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


namespace modules\ip_blocklist\apps\AdminPanel;

class AddIpBlocklistControl implements \modules\ip_blocklist\apps\AdminPanel\IBlocklistControl
{
	public function getControlInfo()
	{
		return array(
			'caption' => 'Add a New IP / IP range',
			'url' => \App()->PageRoute->getSystemPageURI('ip_blocklist', 'add_ip_range'),
			'absoluteUrl' => false,
		);
	}

	public static function getOrder()
	{
		return 100;
	}
}
