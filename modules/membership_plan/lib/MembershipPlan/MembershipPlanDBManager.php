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


namespace modules\membership_plan\lib\MembershipPlan;

class MembershipPlanDBManager extends \lib\ORM\ObjectDBManager
{
	public function saveMembershipPlan($membershipPlan)
	{
		parent::saveObject($membershipPlan);
	}
	public function getMembershipPlanInfoBySID($membershipPlanSID)
	{
		return parent::getObjectInfo('membership_plan_plans', $membershipPlanSID);
	}

	public function getQuantityOfContractsByMembershipPlanSID($membershipPlanSID)
	{
		$result = \App()->DB->query('SELECT COUNT(`users_users`.`contract_sid`) FROM `users_users` LEFT JOIN `membership_plan_contracts` ON `users_users`.`contract_sid` = `membership_plan_contracts`.`sid` LEFT JOIN `membership_plan_plans` ON `membership_plan_plans`.`sid` = `membership_plan_contracts`.`membership_plan_sid` WHERE `membership_plan_plans`.`sid`=?n', $membershipPlanSID);
		$result = array_pop($result);

		return $result ? array_pop($result) : 0;
	}
	public function deleteFromRelations($membershipPlanSID)
	{
		return \App()->DB->query("DELETE FROM `users_relations_user_groups_membership_plans` WHERE `membership_plan_sid` = ?n", $membershipPlanSID);
	}
	public function deleteMembershipPlanBySID($membershipPlanSID)
	{
		return parent::deleteObject('membership_plan_plans', $membershipPlanSID);
	}
	public function getAllMembershipPlansInfo()
	{
		return \App()->DB->query("SELECT * FROM `membership_plan_plans` ORDER BY name");
	}
	public function getAllMembershipPlanSIDsByUserGroupSID($userGroupSID)
	{
		$queryResult = \App()->DB->query('SELECT `membership_plan_sid` FROM `users_relations_user_groups_membership_plans` WHERE `user_group_sid`=?n', $userGroupSID);
				
		return $this->extractColumn($queryResult);

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
