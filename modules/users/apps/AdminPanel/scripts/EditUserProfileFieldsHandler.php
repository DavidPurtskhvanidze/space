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


namespace modules\users\apps\AdminPanel\scripts;

class EditUserProfileFieldsHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'edit_user_profile';

	public function respond()
	{

		$template_processor = \App()->getTemplateProcessor();
		$user_group_sid = isset($_REQUEST['user_group_sid']) ? $_REQUEST['user_group_sid'] : null;		
		$user_group_info = $this->getUserGroupInfo($user_group_sid);
		
		$showForm = true;
		$userProfileFields = array();
		
		$action = \App()->Request->getValueOrDefault('action', null);
		$newOrder = \App()->Request->getValueOrDefault('item', null);
		if($action == 'sort' && !empty($newOrder))
		{
			$object_replacer = \App()->ObjectMother->createUserProfileFieldsReplacer($newOrder,\App()->Request['parentValue']);
			$object_replacer->update();
			die();
		}
		
		if (!is_null($user_group_sid))
		{
			$userProfileFieldsInfo = \App()->UserProfileFieldManager->getFieldsInfoByUserGroupSID($user_group_sid);
			foreach($userProfileFieldsInfo as $fieldInfo)
			{
				$field = \App()->UserProfileFieldManager->getFieldBySID($fieldInfo['sid']);
				$field->addProperty(array
				(
					'id' => 'user_group_sid',
					'type' => 'integer',
					'value' => $fieldInfo['user_group_sid'],
				));
				$userProfileFields[] = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($field);
			}
		}
		else {
			\App()->ErrorMessages->addMessage('EMPTY_USER_GROUP_SID');
			$showForm = false;
		}

		$template_processor->assign("showForm", $showForm);
		$template_processor->assign("user_profile_fields", $userProfileFields);
		$template_processor->assign("user_group_sid", $user_group_sid);
		$template_processor->assign("user_group_info", $user_group_info);
		$template_processor->display("edit_user_profile_fields.tpl");
	}
	
	private function getUserGroupInfo($user_group_sid)
	{
		if ($user_group_sid == 0) return self::$CommonFieldGroupInfo;
		$info = \App()->UserGroupManager->getUserGroupInfoBySID($user_group_sid);
		return $info;
	}
	
	private static $CommonFieldGroupInfo = array (
												  'sid' => '0',
												  'id' => 'All Groups',
												  'name' => 'All Groups',
												  'reg_form_template' => NULL,
												  'description' => NULL,
												  'immediate_activation' => '1',
												  'user_menu_template' => NULL,
												  'make_user_trusted' => NULL,
												);

}
