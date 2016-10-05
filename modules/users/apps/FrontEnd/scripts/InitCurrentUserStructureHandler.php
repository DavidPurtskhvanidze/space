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

class InitCurrentUserStructureHandler extends \apps\FrontEnd\ContentHandlerBase implements \core\IStartupScript
{
	protected $displayName = 'Init Current User Structure';
	protected $moduleName = 'users';
	protected $functionName = 'init_current_user_structure';

	public function respond()
	{
		$current_user_info = new \modules\users\lib\User\CurrentUserTemplateStructureLazyLoadAdapter();
		\App()->GlobalTemplateVariable->setCurrentUserInfo($current_user_info);
	}
}

