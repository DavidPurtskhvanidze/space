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

class SendNotEnoughFundsToExtendContractMessageAction implements IAfterExpireUserContractAction
{
	private $userSid;
	private $reasonCode;

	public function setUserSid($userSid)
	{
		$this->userSid = $userSid;
	}

	public function setReasonCode($reasonCode)
	{
		$this->reasonCode = $reasonCode;
	}

	public function perform()
	{
		if ($this->reasonCode == 'NOT_ENOUGH_FUNDS_TO_EXTEND')
		{
			$userInfo = \App()->UserManager->getUserInfoBySID($this->userSid);
			return \App()->EmailService->send($userInfo['email'], 'email_template:not_enough_funds_to_extend_contract', array('user' => $userInfo));
		}
		return false;
	}
}
