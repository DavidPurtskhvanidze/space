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

class EditUserGroupHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'users';
	protected $functionName = 'edit_user_group';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$user_group_sid = isset($_REQUEST['sid']) ? $_REQUEST['sid'] : null;

		if (!is_null($user_group_sid))
		{
			$user_group_info = \App()->UserGroupManager->getUserGroupInfoBySID($user_group_sid);
			$user_group_info = array_merge($user_group_info, $_REQUEST);

			$user_group = \App()->UserGroupManager->createUserGroup($user_group_info);
			$user_group->setSID($user_group_sid);

			$edit_user_group_form = new \lib\Forms\Form($user_group);
			$form_is_submitted = (isset($_REQUEST['action']) && $_REQUEST['action'] == 'save_info');

			if ($form_is_submitted && $edit_user_group_form->isDataValid())
			{
				\App()->UserGroupManager->saveUserGroup($user_group);
				throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('user_groups'));
			}
			else
			{
				if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'add_membership_plan')
				{
					$membership_plan_sid = isset($_REQUEST['membership_plan_sid']) ? $_REQUEST['membership_plan_sid'] : null;
					\App()->UserGroupManager->addMembershipPlan($user_group_sid, $membership_plan_sid);
				}
				elseif (isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_membership_plan')
				{
					$membership_plan_sid = isset($_REQUEST['membership_plan_sid']) ? $_REQUEST['membership_plan_sid'] : null;
					\App()->UserGroupManager->deleteMembershipPlan($user_group_sid, $membership_plan_sid);
					throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?sid=$user_group_sid");
				}

				$template_processor = \App()->getTemplateProcessor();
				$membership_plan_sids = \App()->UserGroupManager->getMembershipPlanSIDsByUserGroupsID($user_group_sid);
				$membership_plans_info = array();
				$user_sids_in_group = \App()->UserManager->getUserSIDsByUserGroupSID($user_group_sid);
				$user_group_membership_plan_user_number = array();

				foreach ($membership_plan_sids as $membership_plan_sid)
				{
					$membership_plans_info[] = \App()->MembershipPlanManager->getMembershipPlanInfoBySID($membership_plan_sid);
					$user_sids_in_membership_plan = \App()->UserManager->getUserSIDsByMembershipPlanSID($membership_plan_sid);
					$user_number = count(array_intersect($user_sids_in_group, $user_sids_in_membership_plan));
					$user_group_membership_plan_user_number[$membership_plan_sid] = $user_number;
				}

				$edit_user_group_form->registerTags($template_processor);
				$template_processor->assign("object_sid", $user_group->getSID());
				$template_processor->assign("user_group_sid", $user_group_sid);
				$template_processor->assign("user_group_membership_plans_info", $membership_plans_info);
				$template_processor->assign("user_group_membership_plan_user_number", $user_group_membership_plan_user_number);
				$template_processor->assign("form_fields", $edit_user_group_form->getFormFieldsInfo());
				$membership_plans_info = \App()->MembershipPlanManager->getAllMembershipPlansInfo();
				$template_processor->assign("membership_plans_info", $membership_plans_info);
			}
		}
		else
		{
			\App()->ErrorMessages->addMessage('USER_GROUP_SID_NOT_SET');
		}
		$template_processor->assign("user_group_info", isset($user_group_info) ? $user_group_info : null);
		$template_processor->assign("object_sid", isset($_REQUEST['sid']) ? $_REQUEST['sid'] : null);
		$template_processor->display("edit_user_group.tpl");
	}
}
