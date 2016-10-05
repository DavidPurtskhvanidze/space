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

class EditPackageHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'membership_plan';
	protected $functionName = 'edit_package';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$requestReflector = \App()->ObjectMother->createRequestReflector();

		$package = \App()->PackageManager->getPackageBySID($requestReflector->get('sid'));
		$package->incorporateData($_REQUEST);
		$membershipPlan = \App()->MembershipPlanManager->getMembershipPlanForEditingBySID($package->getMembershipPlanSID());

		$form = \App()->PackageManager->getEditingFormForPackage($package);
		$form->registerTags($templateProcessor);

		if (($requestReflector->get('action') == 'save_package') && $form->isDataValid())
		{
			\App()->PackageManager->savePackage($package);
			\App()->SuccessMessages->addMessage('PACKAGE_SAVED');
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('membership_plan_edit') . '?sid=' . $package->getMembershipPlanSID());
		}
		else
		{
			$templateProcessor->assign('membershipPlan', \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($membershipPlan));
			$templateProcessor->assign('packageSID', $package->getSID());
			$templateProcessor->assign('packageClassName', $package->getClassName());
			$templateProcessor->assign('formFields', $form->getFormFieldsInfo());
			$templateProcessor->display('edit_package.tpl');
		}
	}
}
