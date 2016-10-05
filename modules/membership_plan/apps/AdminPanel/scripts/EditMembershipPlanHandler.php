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

class EditMembershipPlanHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'membership_plan';
	protected $functionName = 'edit_membership_plan';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$requestReflector = \App()->ObjectMother->createRequestReflector();

		$membershipPlan = \App()->MembershipPlanManager->getMembershipPlanForEditingBySID($requestReflector->get('sid'));
		$membershipPlan->incorporateData($_REQUEST);

		$form = \App()->MembershipPlanManager->getEditingFormForMembershipPlan($membershipPlan);
		$form->registerTags($templateProcessor);

		if (($requestReflector->get('action') == 'save_membership_plan') && $form->isDataValid())
		{
			\App()->MembershipPlanManager->saveMembershipPlan($membershipPlan);
			$action = \App()->ObjectMother->createMembershipPlanChangedPostAction($membershipPlan);
			$action->perform();
			\App()->SuccessMessages->addMessage('MEMBERSHIP_PLAN_SAVED');
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById("membership_plans"));
		}
		else
		{
			$templateProcessor->assign('packageMessages', array($requestReflector->get('message')));
			$templateProcessor->assign('packageErrors', array($requestReflector->get('error')));
			$templateProcessor->assign('membershipPlanSID', $membershipPlan->getSID());
			$templateProcessor->assign('membershipPlanName', $membershipPlan->getName());
			$templateProcessor->assign('membershipPlanType', $membershipPlan->getType());			
			$templateProcessor->assign('grouped_packages', \App()->PackageManager->getGroupedTypePackagesByMembershipPlanSID($membershipPlan->getSID()));
			$templateProcessor->assign('formFields', $form->getFormFieldsInfo());
			$templateProcessor->display('edit_membership_plan.tpl');
		}
	}
}
