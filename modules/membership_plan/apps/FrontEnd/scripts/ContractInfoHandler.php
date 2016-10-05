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

class ContractInfoHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Contract Info';
	protected $moduleName = 'membership_plan';
	protected $functionName = 'contract_info';

	public function respond()
	{
		$request = \App()->ObjectMother->createRequestReflector();
		$contractId = $request->get('id');

		if (!is_null($contractId))
		{
			if ($request->get('action') == 'change_auto_extend')
			{
				\App()->ContractManager->updateContractAutoExtendFlag($contractId, $request->get('auto_extend'));
			}
			$contract = \App()->ContractManager->getContractBySID($contractId);
			$action = \App()->ObjectMother->createDisplayTemplateAction('contract_info.tpl', array
			(
				"contract" => \App()->ContractManager->createTemplateStructureForContract($contract),
				"is_auto_extension_available" => \App()->PaymentSystemManager->getCurrentPaymentMethod()->areRecurringPaymentsPossible(),
		));
			$action->perform();
		}
	}
}
?>
