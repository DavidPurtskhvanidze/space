<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\apps\FrontEnd\scripts;

class UserMenuBlockHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'User Menu Block';
	protected $moduleName = 'users';
	protected $functionName = 'user_menu_block';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->display('user_menu_block.tpl');
	}
}
