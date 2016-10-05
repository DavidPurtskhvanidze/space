<?php
/**
 *
 *    Module: static_content v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: static_content-7.5.0-1
 *    Tag: tags/7.5.0-1@19836, 2016-06-17 13:22:00
 *
 *    This file is part of the 'static_content' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\static_content;

class Module extends \core\Module
{
	protected $name = 'static_content';
	protected $caption = 'Static Content';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'site_pages',
		'content_management',
	);
}
