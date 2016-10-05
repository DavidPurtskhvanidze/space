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

class ChangeUserGroupHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Change User Group';
	protected $moduleName = 'users';
	protected $functionName = 'change_user_group';
	protected $rawOutput = true;

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$userInfo = \App()->UserManager->getCurrentUserInfo();

		$canPerform = true;
		$validators = new \core\ExtensionPoint('modules\users\apps\FrontEnd\IChangeUserGroupValidator');
		foreach ($validators as $validator)
		{
			$canPerform &= $validator->isValid();
		}
		if (!$canPerform)
		{
			$templateProcessor->display('errors.tpl');
			return;
		}

		if (!empty($userInfo))
		{
			$requestReflector = \App()->ObjectMother->createRequestReflector();
			if ($requestReflector->get('action') == 'change_user_group')
			{
				$groupId = $requestReflector->get('groupId');

				$group_sid = \App()->UserGroupManager->getUserGroupSIDByID($groupId);
				$group_info = \App()->UserGroupManager->getUserGroupInfoBySID($group_sid);
				
				if(!($group_info && $group_info['active'] == "1"))
				{
					$groupId = "";
				}

				$userInfo = array_merge($userInfo, $_REQUEST);
				$user = \App()->UserManager->createUser($userInfo, \App()->UserGroupManager->getUserGroupSIDByID($groupId));
				$user->setSID($userInfo['sid']);

				array_map(array($user, 'deleteProperty'), array('active', 'trusted_user', 'user_group', 'user_group_sid', 'registration_date', 'group', 'password', 'username'));

				$editProfileForm = \App()->ObjectMother->createForm($user);
				$editProfileForm->registerTags($templateProcessor);
				$templateProcessor->assign("form_fields", $editProfileForm->getFormFieldsInfo());

				if ($editProfileForm->isDataValid())
				{
					try
					{
						$oldUserInfo = \App()->UserManager->getUserInfoBySID($user->getSID());
						\App()->UserManager->saveUser($user);
						\App()->UserManager->changeUserGroupByUserSid($user->getSID(), $groupId);
						\App()->UserManager->updateCurrentUserSession();
						
						$afterUserGroupChangedActions = new \core\ExtensionPoint('modules\users\apps\FrontEnd\IAfterUserGroupChangedAction');
						foreach ($afterUserGroupChangedActions as $afterUserGroupChangedAction)
						{
							$afterUserGroupChangedAction->setOriginalUser(\App()->UserManager->createUser($oldUserInfo, $oldUserInfo['user_group_sid']));
							$afterUserGroupChangedAction->setUpdatedUser($user);
							$afterUserGroupChangedAction->perform();
						}
						
						$templateProcessor->assign("actionFinished", true);
						\App()->SuccessMessages->addMessage('GROUP_CHANGED');
					}
					catch (\Exception $e)
					{
						$errors[$e->getMessage()] = $e->getMessage();
						$templateProcessor->assign("errors", $errors);
					}
				}

				if ($requestReflector->get('formIsShownFirstTime'))
				{
					\App()->ErrorMessages->fetchMessages(); //don't show error messages for the first time
				}
				$templateProcessor->assign("groupId", $groupId);
				$templateProcessor->display('change_user_group.tpl');
			}
			else
			{
				$user = \App()->UserManager->createUser($userInfo, $userInfo['user_group_sid']);
				$templateProcessor->assign("userContractId", $user->getContractID());
				$templateProcessor->assign("userGroupInfo", \App()->UserGroupManager->getUserGroupInfoBySID($user->getUserGroupSID()));
				$templateProcessor->assign("userGroupOptions", \App()->UserGroupManager->getActiveUserGroupsInfo());
				$templateProcessor->display('select_user_group.tpl');
			}
		}
	}
}

?>
