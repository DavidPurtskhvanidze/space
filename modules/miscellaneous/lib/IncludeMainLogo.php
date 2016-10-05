<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

class IncludeMainLogo implements \modules\smarty_based_template_processor\lib\IPlugin
{
	public function getPluginType()
	{
		return 'function';
	}

	public function getPluginTag()
	{
		return 'IncludeMainLogo';
	}

	public function getPluginCallback()
	{
		return array($this, 'getHtmlCode');
	}

	public function getHtmlCode($params, $templateProcessor)
	{
		$logoFileName = \App()->SettingsFromDB->getSettingByName('main_logo');
		if (! empty($logoFileName))
		{
			$siteName = \App()->SettingsFromDB->getSettingByName('site_name');
			$siteUrl = \App()->SystemSettings['SiteUrl'];
			$pathToLogo = $siteUrl . '/' . \App()->SystemSettings['PicturesDir'] . $logoFileName;
			return "<a href='{$siteUrl}' ><img class=\"img-responsive\" src=\"{$pathToLogo}\" alt='{$siteName}'/></a>";
		}
		elseif(! empty($params['default_logo']))
		{
			return $templateProcessor->fetch($params['default_logo']);
		}
		else
		{
			return $templateProcessor->fetch('miscellaneous^logo.tpl');
		}

	}
}
 
