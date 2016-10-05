<?php
/**
 *
 *    Module: site_pages v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: site_pages-7.5.0-1
 *    Tag: tags/7.5.0-1@19834, 2016-06-17 13:21:53
 *
 *    This file is part of the 'site_pages' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\site_pages\apps\AdminPanel\scripts;

class RegisterPageLinkHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'site_pages';
	protected $functionName = 'register_page_link';
	protected $rawOutput = true;

	public function respond()
	{
		$pageInfo = \App()->Request['pageInfo'];
		if (!is_null($pageInfo))
		{
			$pageInfo['application_id'] = 'FrontEnd';
			$template_processor = \App()->getTemplateProcessor();
			$template_processor->assign('query', http_build_query($pageInfo));
			$template_processor->assign('caption', \App()->Request['caption']);
			$template_processor->display('register_page_link.tpl');
		}
	}
}
