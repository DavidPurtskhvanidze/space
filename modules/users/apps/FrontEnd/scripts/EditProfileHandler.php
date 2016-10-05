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

use lib\Http\RedirectException;

class EditProfileHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Edit Profile';
	protected $moduleName = 'users';
	protected $functionName = 'edit_profile';

	public function respond()
	{
        $templateProcessor = \App()->getTemplateProcessor();
        $userInfo = \App()->UserManager->getCurrentUserInfo();
        $messages = isset(\App()->Request['message']) ? [\App()->Request['message'] => ""] : [];
        if (!empty($userInfo)) {
			$userInfo = array_merge($userInfo, \App()->Request->getRequest());
            $user = \App()->UserManager->createUser($userInfo, $userInfo['user_group_sid'], false);
            $user->setSID($userInfo['sid']);

            array_map([$user, 'deleteProperty'], ['active', 'trusted_user', 'user_group', 'user_group_sid', 'registration_date', 'group', 'sid']);
            $user->makePropertyNotRequired("password");

            $editProfileForm = \App()->ObjectMother->createForm($user);
            $editProfileForm->registerTags($templateProcessor);
            $editProfileForm->makeDisabled("username");

            $action = isset(\App()->Request['action']) ? \App()->Request['action'] : 'none';
            $errors = isset(\App()->Request['error']) ? [\App()->Request['error'] => ""] : [];

            if ($action == 'save_info')
            {
                $canPerform = true;
				$validators = new \core\ExtensionPoint('modules\users\apps\FrontEnd\IEditUserProfileValidator');
				foreach ($validators as $validator)
				{
					$validator->setUser($user);
					$canPerform &= $validator->isValid();
				}

                if ($canPerform)
				{
                    if ($editProfileForm->isDataValid())
					{
						$passwordValue = $user->getPropertyValue('password');

						if (empty($passwordValue['original'])) {
							$user->deleteProperty('password');
						}

						\App()->UserManager->saveUser($user);
						\App()->UserManager->updateCurrentUserSession();

						//$messages['PROFILE_SAVED'] = '';
						\App()->SuccessMessages->addMessage('PROFILE_SAVED');
                        throw new RedirectException($_SERVER['HTTP_REFERER']);
					}
				}
			}

			$templateProcessor->assign("errors", $errors);
			$templateProcessor->assign("form_fields", $editProfileForm->getFormFieldsInfo());
			$templateProcessor->assign('messages', $messages);
			$templateProcessor->assign("userGroupInfo", \App()->UserGroupManager->getUserGroupInfoBySID($user->getUserGroupSID()));
			$templateProcessor->display('edit_profile.tpl');
		}
	}
}
