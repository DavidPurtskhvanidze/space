<?php
/**
 *
 *    Module: recent_tweets v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: recent_tweets-7.3.0-1
 *    Tag: tags/7.3.0-1@18563, 2015-08-24 13:38:12
 *
 *    This file is part of the 'recent_tweets' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\recent_tweets;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'recent_tweets';
	protected $caption = 'Recent Tweets';
	protected $version = '7.3.0-1';
	protected $dependencies = array
	(
		'third_party_auth_providers',
	);

	public function getModuleTemplateProviderName()
	{
		return "Recent Tweets";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Recent Tweets templates";
	}

	public function getModuleName()
	{
		return "recent_tweets";
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
