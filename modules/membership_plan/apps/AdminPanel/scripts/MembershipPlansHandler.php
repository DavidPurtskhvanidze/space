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

class MembershipPlansHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\users\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'membership_plan';
	protected $functionName = 'membership_plans';

	private $templateProcessor;
	private $requestReflector;
	private $messages = array();
	private $errors = array();

	public function respond()
	{
		$this->requestReflector = \App()->ObjectMother->createRequestReflector();
		$this->templateProcessor = \App()->getTemplateProcessor();

		$this->initMessages();

		if (\App()->Request['action'] == 'delete')
		{
			$this->deleteMembershipPlan(\App()->Request['membership_plan_sid']);
		}

		$this->templateProcessor->assign('messages', $this->messages);
		$this->templateProcessor->assign('errors', $this->errors);
		$this->templateProcessor->assign('membershipPlans', $this->getMembershipPlans($this->getSearch()));
		$this->templateProcessor->display('membership_plans.tpl');
	}

	private function getModelObject()
	{
		return \App()->MembershipPlanManager->createMembershipPlan(array());
	}
	private function getSearch()
	{

		$search = new \lib\ORM\SearchEngine\Search();
		$search->setDB(\App()->DB);
		$search->setRowMapper(new \modules\membership_plan\lib\MembershipPlan\MembershipPlanManagerToRowMapperAdapter());
		$search->setModelObject($this->getModelObject());
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		$search->setRequest($_REQUEST);
		
		$search->setPage(1);
		$search->setObjectsPerPage(1000);

		return $search;
	}
	private function getMembershipPlans($search)
	{
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
	}

	private function initMessages()
	{
		if ($this->requestReflector->get('message'))
		{
			$this->messages[] = $this->requestReflector->get('message');
		}
		if ($this->requestReflector->get('error'))
		{
			$this->errors[] = $this->requestReflector->get('error');
		}
	}

	private function deleteMembershipPlan($membershipPlanSid)
	{
		$canPerform = true;
		$validators = new \core\ExtensionPoint('modules\membership_plan\apps\AdminPanel\IDeleteMembershipPlanValidator');
		foreach ($validators as $validator)
		{
			$validator->setMembershipPlanSid($membershipPlanSid);
			$canPerform &= $validator->isValid();
		}
		if ($canPerform)
		{
			\App()->MembershipPlanManager->deleteMembershipPlanBySID($membershipPlanSid);
			\App()->SuccessMessages->addMessage('MEMBERSHIP_PLAN_DELETED');
		}
	}

	public static function getOrder()
	{
		return 100;
	}

	public function getCaption()
	{
		return "Membership Plans";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('membership_plans');
	}

	public function getHighlightUrls()
	{
		return array
		(
			\App()->PageRoute->getPageURLById('membership_plans'),
			\App()->PageRoute->getPageURLById('membership_plan_add'),
			\App()->PageRoute->getPageURLById('membership_plan_edit'),
			\App()->PageRoute->getPageURLById('membership_plan_package_add'),
			\App()->PageRoute->getPageURLById('membership_plan_package_edit'),
		);
	}
}
