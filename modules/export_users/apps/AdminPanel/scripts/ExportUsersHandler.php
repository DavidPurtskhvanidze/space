<?php
/**
 *
 *    Module: export_users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_users-7.5.0-1
 *    Tag: tags/7.5.0-1@19780, 2016-06-17 13:19:18
 *
 *    This file is part of the 'export_users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_users\apps\AdminPanel\scripts;

class ExportUsersHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\users\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'export_users';
	protected $functionName = 'export_users';

	public function respond()
	{
		$userGroupSid = \App()->Request['user_group_sid'];

		if (\App()->Request['action'] == 'export')
		{
			$properties = empty($_REQUEST['export_properties']) ? array() : $_REQUEST['export_properties'];
            array_unshift($properties, 'username');
			if (empty($userGroupSid))
			{
				$userSids = \App()->UserManager->getAllUserSIDs();
			}
			else
			{
				$userSids = \App()->UserManager->getUserSIDsByUserGroupSID($userGroupSid);
			}

			if (!empty($userSids))
			{
				\App()->Session->getContainer('EXPORT_USERS')->setValue('userSids', $userSids);
				\App()->Session->getContainer('EXPORT_USERS')->setValue('properties', $properties);
				throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'export_users_file'));
			}
			else
			{
				\App()->ErrorMessages->addMessage("EMPTY_EXPORT_DATA");
			}
		}

		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign('user_group_sid', $userGroupSid);
		$template_processor->assign('user_groups', \App()->UserGroupManager->getAllUserGroupsInfo());
		$properties = \App()->UserManager->getUserProperties($userGroupSid);
		$properties = array_filter($properties, create_function('$property', 'return (!isset($property["type"]) || $property["type"] != "picture");'));
		$template_processor->assign('properties', $properties);
		$template_processor->display('export_users.tpl');
	}

	public static function getOrder()
	{
		return 500;
	}

	public function getCaption()
	{
		return "Export Users";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return array
		(
		);
	}
}
