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

class RegistrationHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Show register block';
	protected $moduleName = 'users';
	protected $functionName = 'registration';
	protected $parameters = array('user_group_id');
	private $user_group_id = null;

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		if (\App()->UserManager->isUserLoggedIn())
		{
			$templateProcessor->display("already_logged_in.tpl");
			return;
		}

		$this->defineUserGroupId();

		if (is_null($this->user_group_id))
		{
			$this->showUserGroupList($templateProcessor);
		}
		else
		{
			$user_group_sid = \App()->UserGroupManager->getUserGroupSIDByID($this->user_group_id);
			if(is_null($user_group_sid))
				$this->showUserGroupList($templateProcessor);
			else
				$this->showRegisterForm($templateProcessor, $user_group_sid);
		}
	}

	private function showRegisterForm($templateProcessor, $user_group_sid)
	{
		$user_group_info = \App()->UserGroupManager->getUserGroupInfoBySID($user_group_sid);

		if(!($user_group_info && $user_group_info['active'] == "1"))
		{
			$user_group_sid = NULL;
			$user_group_info = NULL;
		}
		$user = \App()->UserManager->createUser($_REQUEST, $user_group_sid);
		array_map(array($user, 'deleteProperty'), array('active', 'trusted_user', 'user_group', 'user_group_sid', 'registration_date', 'group', 'sid'));

		$registration_form = \App()->ObjectMother->createForm($user, array(), \App()->SettingsFromDB->getSettingByName('captcha_in_registration_form'));
		$registration_form->registerTags($templateProcessor);

		$form_submitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'register');

		if ($form_submitted && $registration_form->isDataValid())
		{
			$availableMembershipPlanSIDs = \App()->MembershipPlanManager->getAllMembershipPlanSIDsByUserGroupSID($user_group_sid);

			if (count($availableMembershipPlanSIDs) == 1)
			{
				$membershipPlanSID = array_pop($availableMembershipPlanSIDs);

				$membershipPlan = \App()->MembershipPlanManager->getMembershipPlanBySID($membershipPlanSID);

				if ($membershipPlan->getPrice() == 0)
				{
					$contract = \App()->ContractManager->createContractByMembershipPlanSID($membershipPlanSID);

					if (\App()->ContractManager->saveContract($contract))
					{
						$user->setContractID($contract->getSID());
					}
				}
			}

			\App()->UserManager->saveUser($user);

			$afterRegisterUserActions = new \core\ExtensionPoint('modules\users\apps\FrontEnd\IAfterRegisterUserAction');

			/**
			 * @var \modules\users\apps\FrontEnd\IAfterRegisterUserAction $afterRegisterUserAction
			 */
			foreach ($afterRegisterUserActions as $afterRegisterUserAction)
			{
				$afterRegisterUserAction->setUser($user);
				$afterRegisterUserAction->perform();
			}

			if (\App()->UserGroupManager->isUserTrustedByDefault($user_group_sid))
			{
				\App()->UserManager->makeTrusted($user->getSID());
			}

			$activation_is_immediate = \App()->UserGroupManager->isActivationImmediateInUserGroup($user_group_sid);

			if ($activation_is_immediate)
			{
				\App()->UserManager->activateUserByUserName($user->getUserName());
				$password = $user->getPropertyValue('password');
				$errors = array();
				\App()->UserManager->login($user->getPropertyValue('username'), $password['original'], true, $errors);
				throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl']);
			}
			else
			{
				$isSended = \App()->UserManager->sendUserActivationLetter($user->getSID());
				if ($isSended)
				{
					$templateProcessor->display("registration_confirm.tpl");
				}
				else
				{
					$templateProcessor->display("registration_failed_to_send_activation_email.tpl");
				}
			}
		}
		else
		{
			$registration_form_template = "registration_form.tpl";

			if (isset($_REQUEST['reg_form_template']))
			{
				$registration_form_template = $_REQUEST['reg_form_template'];
			}
			elseif (!empty($user_group_info['reg_form_template']))
			{
				$registration_form_template = $user_group_info['reg_form_template'];
			}

			$templateProcessor->assign("user_group_id", $this->user_group_id);
			$templateProcessor->assign("form_fields", $registration_form->getFormFieldsInfo());

			$templateProcessor->display($registration_form_template);
		}
	}

	private function showUserGroupList($templateProcessor)
	{
		$userGroupsInfo = \App()->UserGroupManager->getActiveUserGroupsInfo();
		$templateProcessor->assign("user_groups_info", $userGroupsInfo);
		$templateProcessor->display("registration_choose_user_group.tpl");
	}

	private function defineUserGroupId()
	{
		$userGroupsInfo = \App()->UserGroupManager->getActiveUserGroupsInfo();
		if (sizeof($userGroupsInfo) == 1)
		{
            $userGroupsInfo = array_pop($userGroupsInfo);
			$this->user_group_id = $userGroupsInfo['id'];
		}
		else
		{
			$this->user_group_id = isset($_REQUEST['user_group_id']) ? $_REQUEST['user_group_id'] : null;
		}
	}


}
