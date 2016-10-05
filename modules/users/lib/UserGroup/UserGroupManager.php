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

class UserGroupManager extends \lib\ORM\ObjectManager implements \core\IService
{
	public function init()
	{
		$this->dbManager = new \modules\users\lib\UserGroup\UserGroupDBManager();
	}

    private $userGroups = [];
	
	public function getObjectBySid($userGroupSid)
	{
        if (!isset($this->userGroups[$userGroupSid]))
        {
            $this->userGroups[$userGroupSid] = $this->createUserGroup($this->getUserGroupInfoBySID($userGroupSid));
        }

		return $this->userGroups[$userGroupSid];
	}
	
	public function createUserGroup($data)
	{
		$userGroup = new UserGroup();
		$userGroup->setDetails($this->createUserGroupDetails($data));
		return $userGroup;
	}
	
	public function createUserGroupDetails($data)
	{
		$details = new UserGroupDetails();
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildPropertiesWithData($data);
		return $details;
	}
	
	function getAllUserGroups()
	{
		$user_groups_info = $this->dbManager->getAllUserGroupsInfo();
		$user_groups = array();
		foreach ($user_groups_info as $user_group_info)
		{
			$user_group = new UserGroup($user_group_info);
            $user_group->setDetails($this->createUserGroupDetails($user_group_info));
            $user_group->setSID($user_group_info['sid']);
			$user_groups[] = $user_group;
		}
		return $user_groups;
	}
	
	function getActiveUserGroupsInfo()
	{
		$user_groups = $this->dbManager->getAllUserGroupsInfo();
		foreach ($user_groups as $key => $user_group)
			if(!$user_group['active'])
				unset($user_groups[$key]);
		return $user_groups;
	}

	function getAllUserGroupsInfo()
	{
		return $this->dbManager->getAllUserGroupsInfo();
	}
	
	function deleteUserGroupBySID($user_group_sid)
	{
		$this->dbManager->deleteFromRelations($user_group_sid);
		return $this->dbManager->deleteUserGroupInfo($user_group_sid);
	}
	
	function getUserGroupInfoBySID($user_group_sid) {

		return $this->dbManager->getUserGroupInfoBySID($user_group_sid);
		
	}
	
	function getMembershipPlanSIDsByUserGroupSID($user_group_sid) {
		
		return $this->dbManager->getMembershipPlanSIDsByUserGroupSID($user_group_sid);
		
	}
	
	function addMembershipPlan($user_group_sid, $membership_plan_sid) {
		
		$this->dbManager->addMembershipPlan($user_group_sid, $membership_plan_sid);
		
	}
	
	function deleteMembershipPlan($user_group_sid, $membership_plan_sid) {
		
		$this->dbManager->deleteMembershipPlan($user_group_sid, $membership_plan_sid);
		
	}
	
	function saveUserGroup($user_group) {
		
		$this->dbManager->saveUserGroup($user_group);
		
	}
	
	function getUserGroupSIDByID($user_group_id) {
		
		return $this->dbManager->getUserGroupSIDByID($user_group_id);
		
	}

	function getUserGroupNameBySID($user_group_sid) {

		return $this->dbManager->getUserGroupNameBySID($user_group_sid);

	}

	function isActivationImmediateInUserGroup($user_group_sid)
	{
		$user_group_info = $this->getUserGroupInfoBySID($user_group_sid);

		if (!empty($user_group_info))
		{
			return $user_group_info['immediate_activation'];
		}
		else
		{
			return null;
		}
	}

	function getAllUserGroupsIDsAndCaptions()
	{
		$user_groups_info = $this->getAllUserGroupsInfo();

		$user_groups_ids_and_captions = array();

		foreach ($user_groups_info as $user_group_info)
		{
			$user_groups_ids_and_captions[] = array('id' 		=> $user_group_info['id'],
												    'caption'	=> $user_group_info['name']);
		}

		return $user_groups_ids_and_captions;
	}

    function createTemplateStructureForUserGroups()
	{
		$user_groups_info = $this->getAllUserGroupsInfo();

		$structure = array();

		foreach ($user_groups_info as $user_group_info)
		{
			$structure[$user_group_info['id']] = array
			(
				'sid'				=> $user_group_info['sid'],
				'id'				=> $user_group_info['id'],
				'caption'			=> $user_group_info['name'],
				'user_number'		=> \App()->UserManager->getUsersNumberByGroupSID($user_group_info['sid']),
				'active'			=> $user_group_info['active'],
				'reg_form_template'	=> $user_group_info['reg_form_template'],
				'description'		=> $user_group_info['description'],
			);
		}

		return $structure;
	}
	
	function isUserTrustedByDefault($user_group_sid)
	{
		$user_group_info = $this->getUserGroupInfoBySID($user_group_sid);
		return isset($user_group_info['make_user_trusted'])? $user_group_info['make_user_trusted']: false;
	}

	public function getAllUserGroupsCount()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `users_user_groups`");
	}
}
