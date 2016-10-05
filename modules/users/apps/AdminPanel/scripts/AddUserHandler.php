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

class AddUserHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'add_user';

	public function respond()
	{

		$template_processor = \App()->getTemplateProcessor();
//		$template_processor->
		$user_group_id = isset($_REQUEST['user_group_id']) ? $_REQUEST['user_group_id'] : null;
		
		if (is_null($user_group_id))
		{
			$this->displayUserGroupSelection();
			return;
		}
		
		$user_group_sid  = \App()->UserGroupManager->getUserGroupSIDByID($user_group_id);
		$user_group_info = \App()->UserGroupManager->getUserGroupInfoBySID($user_group_sid);
		$userGroupManager = \App()->ObjectMother->createUserGroupManager();
		if ($userGroupManager->isUserTrustedByDefault($user_group_sid)) $_REQUEST['trusted_user'] = isset($_REQUEST['trusted_user']) ? $_REQUEST['trusted_user'] : true;
		$user = \App()->ObjectMother->createUser($_REQUEST, $user_group_sid);
		array_map(array($user,'deleteProperty'),array('active','user_group','user_group_sid', 'registration_date','group','sid'));
		
		$registration_form =\App()->ObjectMother->createForm($user);
		$registration_form->registerTags($template_processor);
		$form_submitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add');

		if ($form_submitted && $registration_form->isDataValid())
		{
			$available_membership_plan_ids = \App()->MembershipPlanManager->getAllMembershipPlanSIDsByUserGroupSID($user_group_sid);
			if (count($available_membership_plan_ids) == 1)
			{
				$membership_plan_id = array_pop($available_membership_plan_ids);
				$membership_plan = \App()->MembershipPlanManager->getMembershipPlanBySID($membership_plan_id);
				if ($membership_plan->getPrice() == 0)
				{
					$contract = \App()->ContractManager->createContractByMembershipPlanSID($membership_plan_id);
					if (\App()->ContractManager->saveContract($contract)) $user->setContractID($contract->getID());
				}
			}
			\App()->UserManager->saveUser($user);

			$afterRegisterUserActions = new \core\ExtensionPoint('modules\users\apps\AdminPanel\IAfterAddUserAction');
			foreach ($afterRegisterUserActions as $afterRegisterUserAction)
			{
				$afterRegisterUserAction->setUser($user);
				$afterRegisterUserAction->perform();
			}

			\App()->UserManager->activateUserByUserName($user->getUserName());
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('users'));
		} 
		else
		{
			$template_processor->assign("user_group", $user_group_info);
			$template_processor->assign("form_fields", $registration_form->getFormFieldsInfo());
			$template_processor->display("add_user.tpl");
		}
	}
	
	private function displayUserGroupSelection()
	{
		$user_groups_info = \App()->UserGroupManager->getAllUserGroupsInfo();
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign("user_groups_info", $user_groups_info);
		$template_processor->display("add_user_choose_user_group.tpl");
	}
}
