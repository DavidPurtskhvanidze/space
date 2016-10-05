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

class ChangeUsernameHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Change username';
	protected $moduleName = 'users';
	protected $functionName = 'change_username';
	protected $rawOutput = true;

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$userInfo = \App()->UserManager->getCurrentUserInfo();
		$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

		if (!empty($userInfo))
		{
			$canPerform = true;
			$validators = new \core\ExtensionPoint('modules\users\apps\FrontEnd\IChangeUsernameValidator');
			foreach ($validators as $validator)
			{
				$canPerform &= $validator->isValid();
			}
			if (!$canPerform)
			{
				$templateProcessor->display('errors.tpl');
				return;
			}

			$userInfo = array_merge($userInfo, $_REQUEST);
			$user = \App()->UserManager->createUser($userInfo, $userInfo['user_group_sid']);

			$neededProperties = array('sid', 'username');
			foreach ($userInfo as $propertyName => $propertyValue)
			{
				if (!in_array($propertyName, $neededProperties))
				{
					$user->deleteProperty($propertyName);
				}
			}
			array_map(array($user, 'deleteProperty'), array('sid','user_group', 'group'));

			$editProfileForm = \App()->ObjectMother->createForm($user);
			$editProfileForm->registerTags($templateProcessor);

			if ($action === 'save' && $editProfileForm->isDataValid())
			{
				\App()->UserManager->saveUser($user);
				$userInfo = \App()->UserManager->getUserInfoByUserName($user->getUserName());
				\App()->UserManager->setSessionForUser($userInfo);
				$templateProcessor->assign("actionFinished", true);
				\App()->SuccessMessages->addMessage('USERNAME_CHANGED');
			}

			$templateProcessor->assign("formFields", $editProfileForm->getFormFieldsInfo());
			$templateProcessor->display('change_username.tpl');
		}
	}
}
