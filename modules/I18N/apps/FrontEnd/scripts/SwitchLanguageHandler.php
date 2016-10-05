<?php
/**
 *
 *    Module: I18N v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: I18N-7.5.0-1
 *    Tag: tags/7.5.0-1@19784, 2016-06-17 13:19:28
 *
 *    This file is part of the 'I18N' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\I18N\apps\FrontEnd\scripts;

class SwitchLanguageHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Switch Language';
	protected $moduleName = 'I18N';
	protected $functionName = 'switch_language';

	public function respond()
	{
		$context = \App()->I18N->getContext();
		$context->getLang(); // switch language if needed
		$site_url = \App()->SystemSettings['SiteUrl'];

		if (isset($_REQUEST['back']))
		{
			$backUri = preg_replace("/([&?]lang=\w+)/", '', $_REQUEST['back']);
			list($scheme) = explode($_SERVER['HTTP_HOST'], $site_url);
			throw new \lib\Http\RedirectException($scheme . $_SERVER['HTTP_HOST'] . html_entity_decode(urldecode($backUri)));
		}
		else
		{
			$HTTP_REFERER = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $site_url;
			if (strpos($HTTP_REFERER, $site_url) === 0)
			{
				$HTTP_REFERER = preg_replace("/([&?]lang=\w+)/", '', $HTTP_REFERER);
				throw new \lib\Http\RedirectException($HTTP_REFERER);
			}
		}
	}
}
