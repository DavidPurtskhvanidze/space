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


namespace modules\export_ip_blocklist;

class Module extends \core\Module
{
	protected $name = 'export_ip_blocklist';
	protected $caption = 'Export IP Blocklist';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'ip_blocklist',
	);
}
