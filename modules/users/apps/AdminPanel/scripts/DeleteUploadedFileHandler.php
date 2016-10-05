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

class DeleteUploadedFileHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'delete_uploaded_file';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$username = \App()->Request['username'];
		$field_id = \App()->Request['field_id'];
		$user_info = \App()->UserManager->getUserInfoByUserName($username);

		if (is_null($field_id) || is_null($username))
		{
			\App()->ErrorMessages->addMessage('PARAMETERS_MISSED');
		}
		elseif (!isset($user_info[$field_id]))
		{
			\App()->ErrorMessages->addMessage('WRONG_PARAMETERS_SPECIFIED');
		}
		else
		{
			$canPerform = true;
			$validators = new \core\ExtensionPoint('modules\users\apps\AdminPanel\IDeleteUserLogoValidator');
			foreach ($validators as $validator)
			{
				$validator->setUsername($username);
				$canPerform &= $validator->isValid();
			}
			if (!$canPerform)
			{
				throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('edit_user') . '?username={$username}');
			}

			$uploaded_file_id = $user_info[$field_id];
			\App()->UploadFileManager->deleteUploadedFileByID($uploaded_file_id);
			$user_info[$field_id] = "";
			$user = \App()->UserManager->createUser($user_info, $user_info['user_group_sid']);
			$user->deleteProperty("active");
			$user->deleteProperty('password');
			$user->setSID($user_info['sid']);
			\App()->UserManager->saveUser($user);
			\App()->SuccessMessages->addMessage('FILE_DELETED');
		}
		$template_processor->assign("username", $username);
		$template_processor->display("delete_uploaded_picture.tpl");
	}
}
