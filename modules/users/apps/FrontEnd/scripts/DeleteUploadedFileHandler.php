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

class DeleteUploadedFileHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Delete Uploaded File';
	protected $moduleName = 'users';
	protected $functionName = 'delete_uploaded_file';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		
		$user_info = \App()->UserManager->getCurrentUserInfo();
		$field_id = isset($_REQUEST['field_id']) ? (string)$_REQUEST['field_id'] : null;

		if (is_null($field_id))
		{
			\App()->ErrorMessages->addMessage('PARAMETERS_MISSED');
		}
		elseif (!isset($user_info[$field_id . '_file_name']))
		{
			\App()->ErrorMessages->addMessage('WRONG_PARAMETERS_SPECIFIED');
		}
		else
		{
			$canPerform = true;
			$validators = new \core\ExtensionPoint('modules\users\apps\FrontEnd\IDeleteUserLogoValidator');
			foreach ($validators as $validator)
			{
				$validator->setUsername($user_info['username']);
				$canPerform &= $validator->isValid();
			}
			if (!$canPerform)
			{
				throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('user_profile'));
			}

			$user = \App()->UserManager->createUser($user_info, $user_info['user_group_sid']);
			$user->deleteProperty("active");
			$user->deleteProperty('password');
			$user->setSID(\App()->UserManager->getCurrentUserSID());
            $user->getProperty($field_id)->type->delete();
			\App()->UserManager->saveUser($user);
			\App()->SuccessMessages->addMessage('FILE_DELETED');
		}
		$template_processor->display("delete_uploaded_file.tpl");
	}
}
