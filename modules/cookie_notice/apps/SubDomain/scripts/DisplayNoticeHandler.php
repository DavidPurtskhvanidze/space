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


namespace modules\cookie_notice\apps\SubDomain\scripts;

class DisplayNoticeHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Display Cookie Notice';
	protected $moduleName = 'cookie_notice';
	protected $functionName = 'display_notice';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->display('cookie_notice.tpl');
	}
}
