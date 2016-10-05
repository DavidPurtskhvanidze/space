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

class SubscriptionPageHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Package';
	protected $moduleName = 'membership_plan';
	protected $functionName = 'subscription_page';

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();

		$current_user = \App()->UserManager->getCurrentUser();

		if (empty($current_user))
		{
			$errors['NOT_LOGGED_IN'] = 1;
			$template_processor->assign("errors", $errors);
			$template_processor->display("errors.tpl");
			return;
		}
		if ($current_user->mayChooseContract())
		{
			if (isset($_REQUEST['membershipPlanSID']))
			{
				$membershipPlanSID = $_REQUEST['membershipPlanSID'];
				$membershipPlan = \App()->MembershipPlanManager->getMembershipPlanBySID($membershipPlanSID);
				$error = null;
				if (in_array($membershipPlanSID, \App()->MembershipPlanManager->getAllMembershipPlanSIDsByUserGroupSID($current_user->getUserGroupSID())))
				{
					if (empty(\App()->Request['returnBackUri']))
					{
						$successPageUri = \App()->PageRoute->getSystemPageURI('membership_plan', 'subscription_postpayment_handler');
					}
					else
					{
						$successPageUri = \App()->Request['returnBackUri'];
					}
					$subscribeUserAction = \App()->ObjectMother->createAssignUserContractAction($membershipPlanSID, $current_user);
					$successPageUrl = \App()->SystemSettings['SiteUrl'] . $successPageUri;
					if ($membershipPlan->getPrice() == 0)
					{
						$subscribeUserAction->perform();
						throw new \lib\Http\RedirectException($successPageUrl);
					}
					else
					{
						$invoiceInfo = array
						(
							'user_sid' => $current_user->getSid(),
							'amount' => $membershipPlan->getPrice(),
							'product_id' => 'SUBSCRIPTION',
							'product_description' => 'Subscription',
							'product_info' => array
							(
								'membership_plan_sid' => $membershipPlanSID,
								'membership_plan_name' => $membershipPlan->getName(),
							),
							'success_action' => $subscribeUserAction,
							'success_page_url' => $successPageUrl,
						);
						$invoice = App()->InvoiceManager->createNewInvoice($invoiceInfo);
						throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath('payment_system', 'make_payment') . '?invoice_sid=' . $invoice->getSID());
					}
				}
				else
				{
					$error = "MEMBERSHIP_PLAN_IS_NOT_AVAILABLE";
				}
				$template_processor->assign("error", $error);
				$template_processor->display("result_choose_contract.tpl");
			}
			else // membership plan id is no givven
			{
				$availableMembershipPlanSIDs = \App()->MembershipPlanManager->getAllMembershipPlanSIDsByUserGroupSID($current_user->getUserGroupSID());
				if (empty($availableMembershipPlanSIDs))
				{
					$errors['NO_AVAILABLE_MEMBERSHIP_PLAN'] = 1;
					$template_processor->assign("ERRORS", $errors);
					$template_processor->display("errors.tpl");
					return;
				}
				if (count($availableMembershipPlanSIDs) == 1)
				{
					throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Navigator->getURI() . "?membershipPlanSID=" . array_pop($availableMembershipPlanSIDs));
				}
				$availableMembershipPlans = array();
				foreach ($availableMembershipPlanSIDs as $membershipPlanSID)
				{
					$membershipPlan = \App()->MembershipPlanManager->getMembershipPlanBySID($membershipPlanSID);
					$availableMembershipPlans[] = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($membershipPlan);
				}

				$template_processor->assign('returnBackUri', \App()->Request['returnBackUri']);
				$template_processor->assign('availableMembershipPlans', $availableMembershipPlans);
				$template_processor->display('subscription_page.tpl');
			}
		}
		else
		{
			$errors['ALREADY_SUBSCRIBED'] = 1;
			$template_processor->assign("contractId", $current_user->getContractID());
			$template_processor->assign("ERRORS", $errors);
			$template_processor->display("errors.tpl");
		}
	}
}
