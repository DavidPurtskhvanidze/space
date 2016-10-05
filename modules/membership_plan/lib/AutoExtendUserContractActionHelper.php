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

class AutoExtendUserContractActionHelper
{
	/**
	 * @var \modules\membership_plan\lib\Contract\Contract
	 */
	private $contract;

	/**
	 * @var \modules\users\lib\User\User
	 */
	private $user;

	/**
	 * @var \modules\membership_plan\lib\MembershipPlan\MembershipPlan
	 */
	private $membershipPlan;

	public function isContractExtensionPossible()
	{
		return ($this->contract->isAutoExtend() && \App()->PaymentSystemManager->getCurrentPaymentMethod()->areRecurringPaymentsPossible());
	}

	public function isFreeCriterion()
	{
		return $this->membershipPlan->getPrice() == 0;
	}

	public function doesUserHaveEnoughFunds()
	{
		$userBalanceManager = \App()->PaymentSystemManager->getCurrentPaymentMethod()->getUserBalanceManager();
		return $userBalanceManager->doesUserHaveEnoughFunds($this->user->getSID(), $this->membershipPlan->getPrice());
	}

	public function chargeUser()
	{
		$userBalanceManager = \App()->PaymentSystemManager->getCurrentPaymentMethod()->getUserBalanceManager();
		$userBalanceManager->chargeUser($this->user->getSID(), $this->membershipPlan->getPrice(), 'SUBSCRIPTION_EXTENDED', array());
	}

	public function expireContract($reasonCode)
	{
		$expireContractAction = \App()->ObjectMother->createExpireUserContractAction($this->contract);
		$expireContractAction->perform();
		$this->performActionsAfterUserContractExpiration($reasonCode);
	}

	public function extendContract()
	{
		$extendContractAction = \App()->ObjectMother->createSequenceAction();
		$extendContractAction->push(\App()->ObjectMother->createExpireUserContractAction($this->contract));
		$extendContractAction->push(\App()->ObjectMother->createAssignUserContractAction($this->membershipPlan->getId(), $this->user, true));
		$extendContractAction->perform();
		$this->performActionsAfterUserContractExtended();
	}

	public function sendAdminUserContractExpiredMessage()
	{
		$adminReporter = \App()->ObjectMother->getAdminReporter();
		$adminReporter->addUserContractExpired($this->user);
	}

	public function setContract($contract)
	{
		$this->contract = $contract;
	}

	public function setUser($user)
	{
		$this->user = $user;
	}

	public function setMembershipPlan($membershipPlan)
	{
		$this->membershipPlan = $membershipPlan;
	}

	public function performActionsAfterUserContractExpiration($reasonCode)
	{

		$afterExpireUserContractActions = new \core\ExtensionPoint('modules\membership_plan\lib\IAfterExpireUserContractAction');
		/**
		 * @var IAfterExpireUserContractAction $afterExpireUserContractAction
		 */
		foreach ($afterExpireUserContractActions as $afterExpireUserContractAction)
		{
			$afterExpireUserContractAction->setUserSid($this->user->getSID());
			$afterExpireUserContractAction->setReasonCode($reasonCode);
			$afterExpireUserContractAction->perform();
		}
	}

	public function performActionsAfterUserContractExtended()
	{
		$afterExtendUserContractActions = new \core\ExtensionPoint('modules\membership_plan\lib\IAfterExtendUserContractAction');
		/**
		 * @var IAfterExtendUserContractAction $afterExtendUserContractAction
		 */
		foreach ($afterExtendUserContractActions as $afterExtendUserContractAction)
		{
			$afterExtendUserContractAction->setUserSid($this->user->getSID());
			$afterExtendUserContractAction->perform();
		}
	}

	public function getContract()
	{
		return $this->contract;
	}
}
