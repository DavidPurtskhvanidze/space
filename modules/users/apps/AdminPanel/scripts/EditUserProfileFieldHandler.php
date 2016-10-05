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

use apps\AdminPanel\ContentHandlerBase;
use lib\Forms\Form;
use lib\Http\RedirectException;

class EditUserProfileFieldHandler extends ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'edit_user_profile_field';

	public function respond()
	{

		$template_processor = \App()->getTemplateProcessor();
		$group_sid 	 	= isset($_REQUEST['user_group_sid']) ? $_REQUEST['user_group_sid'] : null;
		$group_info = \App()->UserGroupManager->getUserGroupInfoBySID($group_sid);
		$field_sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : null;
		
		if (!is_null($field_sid))
		{
			$field_info = \App()->UserProfileFieldManager->getInfoBySID($field_sid);
			$userProfileFieldType = isset($field_info['type']) ? $field_info['type'] : null;
			$originalField = \App()->UserProfileFieldManager->createUserProfileField($field_info, $userProfileFieldType);
			$field = \App()->UserProfileFieldManager->createUserProfileField($field_info, $userProfileFieldType);
			$field->incorporateData($_REQUEST);
			$field->setSID($field_sid);
			$field->setUserGroupSID($group_sid);
			$form = new Form($field);
			$form_submitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'save_info');

			if ($form_submitted && $form->isDataValid())
			{
				\App()->UserProfileFieldManager->updateColumnForField($originalField, $field);
				\App()->UserProfileFieldManager->saveUserProfileField($field);
				throw new RedirectException(\App()->PageRoute->getPagePathById('edit_user_profile') . '?user_group_sid=' . $group_sid);
			}
			else
			{
				$form->registerTags($template_processor);
		
				$form->makeDisabled("type");
		
				$template_processor->assign('user_group_sid', $group_sid);
				$template_processor->assign("form_fields", $form->getFormFieldsInfo());
				$template_processor->assign("field_type", $field->getFieldType());
				$template_processor->assign("user_profile_field_info", $field_info);
				$template_processor->assign("user_profile_field_sid", $field_sid);
				
				$template_processor->assign("user_group_info", $group_info);
				$template_processor->display("edit_user_profile_field.tpl");
			}
		}
	}
}
