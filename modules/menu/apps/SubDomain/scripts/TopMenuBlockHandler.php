<?php
/**
 *
 *    Module: menu v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: menu-7.5.0-1
 *    Tag: tags/7.5.0-1@19799, 2016-06-17 13:20:07
 *
 *    This file is part of the 'menu' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\menu\apps\SubDomain\scripts;

class TopMenuBlockHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Menu';
	protected $moduleName = 'menu';
	protected $functionName = 'top_menu';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->display('top_menu.tpl');
	}
}
