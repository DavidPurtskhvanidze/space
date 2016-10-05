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


namespace modules\membership_plan\lib\Contract;

class ContractDBManager extends \lib\ORM\ObjectDBManager
{
	public function delete($contractSID) {
		return \App()->DB->query('DELETE FROM `membership_plan_contracts` WHERE `sid`=?s', $contractSID);
	}
	public function getExpiredSubscriptionContractsBeetwen($periodStart, $periodEnd)
	{
		$dateCondition = "BETWEEN '$periodStart' AND '$periodEnd'";
		return \App()->DB->query("SELECT `sid` FROM `membership_plan_contracts` WHERE `expired_date` {$dateCondition} AND `type` = 'Subscription'");
	}
	public function getExpiredFeeBasedContractsBeetwen($periodStart, $periodEnd)
	{
		$dateCondition = "BETWEEN '$periodStart' AND '$periodEnd'";
		return \App()->DB->query("SELECT `membership_plan_contracts`.`sid` AS `sid` FROM `membership_plan_contracts`, `membership_plan_plans` WHERE `membership_plan_contracts`.`type` = 'Fee Based' AND `membership_plan_contracts`.`membership_plan_sid` = `membership_plan_plans`.`sid` AND DATE_ADD(`membership_plan_contracts`.`creation_date`, INTERVAL `membership_plan_plans`.`subscription_period` DAY ) {$dateCondition}");
	}
	public function getContractInfoBySID($contractSID)
	{
		$sqlResult = \App()->DB->query('SELECT * FROM `membership_plan_contracts` WHERE `sid`=?n', $contractSID);

		return array_pop($sqlResult);
	}
	public function updateContractAutoExtendFlag($contractSID, $autoExtendFlag)
	{
		return \App()->DB->query('UPDATE `membership_plan_contracts` SET `auto_extend` = ?n WHERE `sid` = ?n', $autoExtendFlag, $contractSID);
	}
	public function getAutoExtendContractSIDsByMembershipPlanSID($membershipPlanSID)
	{
		$queryResult = \App()->DB->query('SELECT `sid` FROM `membership_plan_contracts` WHERE `auto_extend` = 1 AND `membership_plan_sid` = ?n', $membershipPlanSID);

		return $this->extractColumn($queryResult);
	}
	public function disableAutoExtendForContractsBySIDs($contractsSIDs)
	{
		return \App()->DB->query('UPDATE `membership_plan_contracts` SET `auto_extend` = 0 WHERE `sid` IN (?l)', $contractsSIDs);
	}
	public function getContractSIDsByMembershipPlanSID($membershipPlanSID)
	{
		$queryResult = \App()->DB->query('SELECT `sid` FROM `membership_plan_contracts` WHERE `membership_plan_sid` = ?n', $membershipPlanSID);

		return $this->extractColumn($queryResult);
	}
	public function getMembershipPlanSidByContractSid($contractSid)
	{
		return \App()->DB->getSingleValue('SELECT `membership_plan_sid` FROM `membership_plan_contracts` WHERE `sid` = ?n', $contractSid);
	}
	public function saveContract($contract)
	{
		$contractInfo = $contract->getHashedFields();
		if (!empty($contractInfo['expired_date']))
		{
			$contractSID = \App()->DB->query(
				'INSERT INTO `membership_plan_contracts`(`membership_plan_sid`, `type`, `creation_date`, `expired_date`, `auto_extend`, `serialized_extra_info`) VALUES(?n, ?s, ?s, ?s, ?n, ?s)',
				$contractInfo['membership_plan_sid'], $contractInfo['type'], $contractInfo['creation_date'], $contractInfo['expired_date'], $contractInfo['auto_extend'], serialize($contract->getExtraInfo())
			);
		}
		else
		{
			$contractSID = \App()->DB->query(
				'INSERT INTO `membership_plan_contracts`(`membership_plan_sid`, `type`, `creation_date`, `auto_extend`, `serialized_extra_info`) VALUES(?n, ?s, ?s, ?n, ?s)',
				$contractInfo['membership_plan_sid'], $contractInfo['type'], $contractInfo['creation_date'], $contractInfo['auto_extend'], serialize($contract->getExtraInfo())
			);
		}
		$contract->setSID($contractSID);

		return true;
	}
	private function extractColumn($queryResult)
	{
		foreach($queryResult as &$record)
		{
			$record = array_shift($record);
		}

		return $queryResult;
	}
}
