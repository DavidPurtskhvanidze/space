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

class SendUserContractExtendedMessageAction implements IAfterExtendUserContractAction
{
	private $userSid;

	public function setUserSid($userSid)
	{
		$this->userSid = $userSid;
	}

	public function perform()
	{
		$userInfo = \App()->UserManager->getUserInfoBySID($this->userSid);
		return \App()->EmailService->send($userInfo['email'], 'email_template:user_contract_extended', array('user' => $userInfo));
	}
}
