<?php
/**
 *
 *    Module: cookie_notice v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: cookie_notice-7.5.0-1
 *    Tag: tags/7.5.0-1@19774, 2016-06-17 13:19:03
 *
 *    This file is part of the 'cookie_notice' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\cookie_notice\apps\FrontEnd;

class BodyTopTemplateDisplayer implements \modules\main\apps\FrontEnd\IBodyTopTemplateDisplayer
{
	public function display()
	{
		$urlPath = parse_url(\App()->SystemSettings['SiteUrl'], PHP_URL_PATH);
		$urlPath = is_null($urlPath) ? "/" : $urlPath;

		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('urlPath', $urlPath);
		$templateProcessor->display('cookie_notice^cookie_notice_balloon.tpl');
	}
}
