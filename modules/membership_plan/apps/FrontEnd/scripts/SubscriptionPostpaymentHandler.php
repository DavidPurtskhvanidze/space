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


namespace modules\membership_plan\apps\FrontEnd\scripts;

class SubscriptionPostpaymentHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Package';
	protected $moduleName = 'membership_plan';
	protected $functionName = 'subscription_postpayment_handler';

	public function respond()
	{
		/**
		 * There are 2 ways that user gets this page:
		 *  1. After successful payment for subscription
		 *  2. After successful subscription for free membership plan
		 * $_REQUEST contains parameter 'invoice_sid' only for the 1st way.
		 * On the second it does not as invoice isn't created.
		 * It MUST be taken into account during the script modifications.
		 */

        $membershipPlanSID = \App()->UserManager->getMembershipPlanSIDByUserSID(\App()->UserManager->getCurrentUserSID());
        $membershipPlan = \App()->MembershipPlanManager->getMembershipPlanBySID($membershipPlanSID);

        $templateProcessor =\App()->getTemplateProcessor();
        $templateProcessor->assign('membershipPlanName', $membershipPlan->getName());
        $templateProcessor->display('result_choose_contract.tpl');
	}
}
