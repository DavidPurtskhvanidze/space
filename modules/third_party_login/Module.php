<?php
/**
 *
 *    Module: third_party_login v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: third_party_login-7.3.0-1
 *    Tag: tags/7.3.0-1@18640, 2015-08-24 13:43:11
 *
 *    This file is part of the 'third_party_login' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\third_party_login;

class Module extends \core\Module implements \apps\IModuleTemplateProvider
{
	protected $name = 'third_party_login';
	protected $caption = 'Facebook, Twitter and OpenID Login';
	protected $version = '7.3.0-1';
	protected $dependencies = array
	(
		'users',
		'third_party_auth_providers',
	);

	public function getModuleTemplateProviderName()
	{
		return "Facebook, Twitter and OpenID Login";
	}

	public function getModuleTemplateProviderDescription()
	{
		return "Facebook, Twitter and OpenID Login templates";
	}

	public function getModuleName()
	{
		return "third_party_login";
	}

	public function getId()
	{
		return __CLASS__;
	}

	public function getAppIds()
	{
		return array('FrontEnd', 'MobileFrontEnd');
	}
}
