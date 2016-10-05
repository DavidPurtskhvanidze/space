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

class AutoExtendUserContractTask extends \modules\miscellaneous\lib\ScheduledTaskBase
{
	public static function getOrder()
	{
		return 100;
	}
	public function run()
	{
		$this->scheduler->log('Starting User Contract Autoextension');
		$expiredContracts = \App()->ContractManager->getSIDsOfContractsExpiredBeetwen($this->scheduler->getLastRunTime(), $this->scheduler->getStartTime());
		$this->scheduler->log(sprintf('Found %d expired contracts. %s', count($expiredContracts), join(', ', $expiredContracts)));
		foreach ($expiredContracts as $contractId)
		{
			$action = \App()->ObjectMother->createAutoExtendUserContractAction($contractId);
			if ($action->canPerform()) 
			{
				$action->perform();
			}
			else
			{
				$this->scheduler->log("Unable to extend contract $contractId: " . join(', ', $action->getErrors()));
			}
		}
	}
}
