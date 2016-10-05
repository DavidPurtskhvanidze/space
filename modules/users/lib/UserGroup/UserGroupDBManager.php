<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\lib\UserGroup;

class UserGroupDBManager extends \lib\ORM\ObjectDBManager
{
	function deleteUserGroupInfo($user_group_sid) {
		
		return parent::deleteObjectInfoFromDB('users_user_groups', $user_group_sid);
		
	}
	
	function getAllUserGroupsInfo() {
		
		return parent::getObjectsInfoByType("users_user_groups");
		
	}
	
	
	function getUserGroupInfoBySID($user_group_sid) {
		return parent::getObjectInfoCached("users_user_groups", $user_group_sid);
		
	}
	
	function getMembershipPlanSIDsByUserGroupSID($user_group_sid) {
		
		$membership_plans_info = \App()->DB->query("SELECT `membership_plan_sid` FROM `users_relations_user_groups_membership_plans` WHERE `user_group_sid` = ?n", $user_group_sid);
		
		$sids = array();
		
		foreach ($membership_plans_info as $membership_plan_info) {
			
			$sids[] = $membership_plan_info['membership_plan_sid'];
			
		}
		
		return $sids;
		
	}
	
	function addMembershipPlan($user_group_sid, $membership_plan_sid) {
		
		if ($user_group_sid && $membership_plan_sid) {
			
			$does_exist = \App()->DB->getSingleValue("SELECT COUNT(*) FROM `users_relations_user_groups_membership_plans` WHERE user_group_sid = ?n AND membership_plan_sid = ?n", $user_group_sid, $membership_plan_sid);

			if (!$does_exist) {
				
				return  \App()->DB->query("INSERT INTO `users_relations_user_groups_membership_plans` VALUES(?n, ?n)", $user_group_sid, $membership_plan_sid);
				
			}
		
		}
		
		return false;
		
	}
	
	function deleteFromRelations($user_group_sid)
	{
		$userProfileFieldsSids = \App()->UserProfileFieldManager->getFieldsSidByUserGroupSid($user_group_sid);
		array_walk($userProfileFieldsSids, array(\App()->UserProfileFieldManager, 'deleteUserProfileFieldBySID'));
		\App()->DB->query("DELETE FROM `users_relations_user_groups_membership_plans` WHERE user_group_sid = ?n", $user_group_sid);
	}
	
	function deleteMembershipPlan($user_group_sid, $membership_plan_sid) {
		
		return  \App()->DB->query("DELETE FROM `users_relations_user_groups_membership_plans` WHERE `user_group_sid` = ?n AND `membership_plan_sid` = ?n", $user_group_sid, $membership_plan_sid);
		
	}
	
	function saveUserGroup($user_group) {
		
		parent::saveObject($user_group);
		
	}
	
	function getUserGroupSIDByID($user_group_id) {
		
		$sid = \App()->DB->getSingleValue("SELECT sid FROM `users_user_groups` WHERE id = ?s", $user_group_id);
		
		if (!empty($sid)) return $sid;
		else			  return null;		
	}

	function getUserGroupNameBySID($user_group_sid) {

		$user_group_info = parent::getObjectInfo("users_user_groups", $user_group_sid);

		return $user_group_info['name'];

	}
}
