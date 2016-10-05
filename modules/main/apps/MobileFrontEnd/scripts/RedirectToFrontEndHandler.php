<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\main\apps\MobileFrontEnd\scripts;

class RedirectToFrontEndHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $displayName = 'Redirect to FrontEnd';
	protected $moduleName = 'main';
	protected $functionName = 'redirect_to_front_end';

	public function respond()
	{
		$urlParams = isset($_REQUEST['params']) ? $_REQUEST['params'] : '';
		$frontEndSiteURL = \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl');

		if (strlen($urlParams) > 1)
		{
			if (strpos($urlParams, '?') === false)
			{
				$urlParams .= "?mobile_redirect=0";
			}
			else
			{
				$urlParams .= "&mobile_redirect=0";
			}
		}
		else
		{
			$urlParams .= "?mobile_redirect=0";
		}
		throw new \lib\Http\RedirectException($frontEndSiteURL . $urlParams);
	}
}
?>
