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

class AddUserProfileFieldHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'add_user_profile_field';

	public function respond()
	{
		$user_group_sid = isset($_REQUEST['user_group_sid']) ? $_REQUEST['user_group_sid'] : null;
		$user_group_info = \App()->UserGroupManager->getUserGroupInfoBySID($user_group_sid);
		$userProfileFieldType = isset($_REQUEST['type']) ? $_REQUEST['type'] : null;

		$field = \App()->UserProfileFieldManager->createUserProfileField($_REQUEST, $userProfileFieldType);
		$field->setUserGroupSID($user_group_sid);

		$form = new \lib\Forms\Form($field);

		$form_is_submitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add');

		$idIsValid = true;
		if (\App()->UserProfileFieldManager->fieldExists($field->getId()))
		{
			\App()->ErrorMessages->addMessage('NOT_UNIQUE_VALUE', array('fieldCaption' => 'Id'));
			$idIsValid = false;
		}
        if ($form_is_submitted && $form->isDataValid() && $idIsValid)
        {
            \App()->UserProfileFieldManager->addColumnToTableForField($field);
			\App()->UserProfileFieldManager->saveUserProfileField($field);
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('edit_user_profile') . '?user_group_sid=' . $user_group_sid);
		}
		else
		{
			$template_processor = \App()->getTemplateProcessor();

			$form->registerTags($template_processor);
			$template_processor->assign("form_fields", $form->getFormFieldsInfo());
			$template_processor->assign("user_group_sid", $user_group_sid);
			$template_processor->assign("user_group_info", $user_group_info);
			$template_processor->display("add_user_profile_field.tpl");
		}
	}
}
