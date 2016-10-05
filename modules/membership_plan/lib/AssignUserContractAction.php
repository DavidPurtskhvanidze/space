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


namespace modules\membership_plan\lib;

class AssignUserContractAction
{
	var $membership_plan_id;
	var $user;
	var $userManager;
	var $autoExtend;
	private $contractManager;

	public function setContractManager($contractManager)
	{
		$this->contractManager = $contractManager;
	}
	
	function setAutoExtend($autoExtend)
	{
		$this->autoExtend = $autoExtend;
	}
	
	function setMembershipPlanId($membership_plan_id)
	{
		$this->membership_plan_id = $membership_plan_id;
	}
	
	function setUser(&$user)
	{
		$this->user = $user;
	}
	
	function setUserManager(&$userManager)
	{
		$this->userManager = $userManager;
	}
	
	function perform()
	{
		$contract = $this->contractManager->createContractByMembershipPlanSID($this->membership_plan_id);

		$contract->setAutoExtend($this->autoExtend);
		if ($this->contractManager->saveContract($contract))
		{
			$this->user->setContractID($contract->getSID());
			$this->user->deleteProperty("password");
			$this->userManager->saveUser($this->user);
		}
	}
}
