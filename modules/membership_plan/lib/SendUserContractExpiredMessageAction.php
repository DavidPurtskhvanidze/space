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

class SendUserContractExpiredMessageAction extends \modules\users\apps\FrontEnd\AbstractUserNotification implements IAfterExpireUserContractAction
{
	private $reasonCode;
	private $userSid;

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
		if ($this->reasonCode == 'CONTRACT_EXPIRED' && $this->getValue($this->userSid))
		{
			$user_info = \App()->UserManager->getUserInfoBySID($this->userSid);
			return \App()->EmailService->send($user_info['email'], 'email_template:contract_expired', array('user' => $user_info));
		}
		return false;
	}

	public function getId()
	{
		return 'contract_expiration';
	}

	public function getCaption()
	{
		return 'Notify on Contract Expiration';
	}
}
