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

class AddPackageHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'membership_plan';
	protected $functionName = 'add_package';

	private $packageType;
	private $allPackageTypes;

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$this->definePackageClassName();

		if (is_null($this->packageType))
		{
			$this->showPackageTypeList($templateProcessor);
		}
		else
		{
			$requestReflector = \App()->ObjectMother->createRequestReflector();

			$membershipPlan = \App()->MembershipPlanManager->getMembershipPlanBySID($requestReflector->get('membership_plan_sid'));
			$package = \App()->PackageManager->createPackage(null, $this->packageType, $membershipPlan->getSID(), $_REQUEST);

			$form = \App()->PackageManager->getCreatingFormForPackage($package);
			$form->registerTags($templateProcessor);

			if (($requestReflector->get('action') == 'save_package') && $form->isDataValid())
			{
				\App()->PackageManager->savePackage($package);
				throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('membership_plan_edit') . '?sid=' . $package->getMembershipPlanSID());
			}
			else
			{
				$templateProcessor->assign('membershipPlan', \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($membershipPlan));
				$templateProcessor->assign('className', $package->getClassName());
				$templateProcessor->assign('formFields', $form->getFormFieldsInfo());
				$templateProcessor->display('add_package.tpl');
			}
		}
	}

	private function showPackageTypeList($tp)
	{
		$membershipPlan = \App()->MembershipPlanManager->getMembershipPlanBySID(\App()->Request['membership_plan_sid']);
		$tp->assign('membershipPlan', \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($membershipPlan));
		$tp->assign("package_types", $this->allPackageTypes);
		$tp->assign("membership_plan_sid", \App()->Request['membership_plan_sid']);
		$tp->display("choose_package_type.tpl");
	}

	private function definePackageClassName()
	{
		$this->allPackageTypes = \App()->PackageManager->getPackageTypes();
		if (sizeof($this->allPackageTypes) == 1)
		{
			$packagesType = array_pop($this->allPackageTypes);
			$this->packageType = $packagesType['id'];
		}
		else
		{
			$this->packageType = isset(\App()->Request['class_name']) ? \App()->Request['class_name'] : null;
		}
	}

}
