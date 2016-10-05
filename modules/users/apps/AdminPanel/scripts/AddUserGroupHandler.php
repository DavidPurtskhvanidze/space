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

class AddUserGroupHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'add_user_group';

	public function respond()
	{
		$user_group = \App()->UserGroupManager->createUserGroup($_REQUEST);
		$add_user_group_form = new \lib\Forms\Form($user_group);

		$form_is_submitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add');

		if ($form_is_submitted && $add_user_group_form->isDataValid())
		{
			\App()->UserGroupManager->saveUserGroup($user_group);
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('user_groups'));
		}
		else
		{
			$template_processor = \App()->getTemplateProcessor();
			$add_user_group_form->registerTags($template_processor);
			$template_processor->assign("form_fields", $add_user_group_form->getFormFieldsInfo());
			$template_processor->display("add_user_group.tpl");
		}
	}
}
