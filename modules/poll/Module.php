<?php
/**
 *
 *    Module: poll v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: poll-7.5.0-1
 *    Tag: tags/7.5.0-1@19804, 2016-06-17 13:20:21
 *
 *    This file is part of the 'poll' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\poll;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'poll';
	protected $caption = 'Poll';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'site_pages',
		'content_management',
	);

	public function getModuleTemplateProviderName()
	{
		return "Poll";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Poll block, answers and results templates";
	}

	public function getModuleName()
	{
		return "poll";
	}

	public function getId()
	{
		return __CLASS__;
	}

	public function getAppIds()
	{
		return array('FrontEnd');
	}
}
