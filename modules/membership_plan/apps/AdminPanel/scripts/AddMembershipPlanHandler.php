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


namespace modules\membership_plan\apps\AdminPanel\scripts;

class AddMembershipPlanHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'membership_plan';
	protected $functionName = 'add_membership_plan';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$requestReflector = \App()->ObjectMother->createRequestReflector();

		$membershipPlan = \App()->MembershipPlanManager->createMembershipPlan($_REQUEST);

		$form = \App()->MembershipPlanManager->getCreatingFormForMembershipPlan($membershipPlan);
		$form->registerTags($templateProcessor);

		if (($requestReflector->get('action') == 'save_membership_plan') && $form->isDataValid())
		{
			\App()->MembershipPlanManager->saveMembershipPlan($membershipPlan);
			\App()->SuccessMessages->addMessage('MEMBERSHIP_PLAN_ADDED');
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('membership_plans'));
		}
		else
		{
			$templateProcessor->assign('formFields', $form->getFormFieldsInfo());
			$templateProcessor->display('add_membership_plan.tpl');
		}
	}
}
