<?php
/**
 *
 *    Module: membership_plan v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: membership_plan-7.5.0-1
 *    Tag: tags/7.5.0-1@19798, 2016-06-17 13:20:05
 *
 *    This file is part of the 'membership_plan' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\membership_plan\apps\FrontEnd\scripts;

class UserMembershipPlansHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Available Membership Plans';
	protected $moduleName = 'membership_plan';
	protected $functionName = 'user_membership_plans';

	public function respond()
	{
		$request = \App()->ObjectMother->createRequestReflector();
		$membershipPlanManager = \App()->MembershipPlanManager;

		$user = \App()->UserManager->getObjectBySID($request->get('user_sid'));
		$membershipPlanSIDs = $membershipPlanManager->getAllMembershipPlanSIDsByUserGroupSID($user->getUserGroupSID());

		$membershipPlans = array();
		foreach ($membershipPlanSIDs as $membershipPlanSID)
		{
			$membershipPlans[] = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter(
				\App()->MembershipPlanManager->createTemplateStructureForMembershipPlan($membershipPlanSID)
			);
		}

		$action = \App()->ObjectMother->createDisplayTemplateAction('user_membership_plans.tpl', array('membershipPlans' => $membershipPlans));
		$action->perform();
	}
}
