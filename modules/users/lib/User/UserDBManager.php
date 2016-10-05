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


namespace modules\users\lib\User;

class UserDBManager extends \lib\ORM\ObjectDBManager {
	
	function saveUser(&$user) {
		
		$user_group_sid = $user->getuserGroupSID();
		
		$user_exists = !is_null($user->getSID());

		if (!is_null($user_group_sid)) {

			parent::saveObject($user);

			if (!$user_exists) {
		
				\App()->DB->query("UPDATE `users_users`
						   SET registration_date = NOW(), activation_key=?s, verification_key=?s
						   WHERE sid = ?n",
						   $user->getActivationKey(), $user->getVerificationKey(), $user->getSID());
				
			}
			
			$user_contract_id = $user->getContractID();
			
			\App()->DB->query("UPDATE `users_users` SET contract_sid = ?n WHERE sid = ?n", $user_contract_id, $user->getSID());
				
			return \App()->DB->query("UPDATE `users_users` SET user_group_sid = ?n WHERE sid = ?n", $user_group_sid, $user->getSID());
			
			
		} else {
			
			return false;
			
		}
		
	}
	
	function getAllUsersInfo() {
		
		return parent::getObjectsInfoByType("users_users");
		
	}
	
	function deleteUserBySid($userSid)
	{
		return parent::deleteObjectInfoFromDB('users_users', $userSid);
	}
	
	function activateUserByUserName($username) {
		                                      
		return \App()->DB->query("UPDATE `users_users` SET `active` = 1 WHERE `username` = ?s", $username);
		
	}
	
	function deactivateUserByUserName($username) {
		
		\App()->DB->query("UPDATE `users_users` SET `active` = 0 WHERE `username` = ?s", $username);
		
	}
	
	function getUserInfoByUserName($username)
	{
		$user_sid = \App()->DB->getSingleValue("SELECT sid FROM `users_users` WHERE `username` = ?s", $username);
		return is_null($user_sid) ? null : parent::getObjectInfo("users_users", $user_sid);
	}

	function getUserInfoByEmail($email)
	{
		$user_sid = \App()->DB->getSingleValue("SELECT sid FROM `users_users` WHERE `email` = ?s", $email);
		return is_null($user_sid) ? null : parent::getObjectInfo("users_users", $user_sid);
	}

	function getUserInfoBySID($user_sid) {
		
		return parent::getObjectInfo("users_users", $user_sid);
		
	}
	
	function getUserNameByUserSID($user_sid) {
				
		$username = \App()->DB->getSingleValue("SELECT `username` FROM `users_users` WHERE `sid` = ?n", $user_sid);

		if (!empty($username)) {

			return $username;

		} else {

			return null;

		}
		
	}

	function getUserSIDsLikeUsername($username)
	{
		if (empty($username)) return null;
		
		$users_info = \App()->DB->query("SELECT `sid` FROM `users_users` WHERE `username` LIKE ?s", "%" . $username . "%");

		if (!empty($users_info))
		{
			foreach ($users_info as $id => $user_info)
			{
				$users_sids[$user_info['sid']] = $user_info['sid'];
			}

			return $users_sids;
		}
		else return null;
	}
	
	function getUserSIDByContractID($contract_id) {
		
		$user_info = \App()->DB->getSingleValue("SELECT sid FROM `users_users` WHERE contract_sid = ?n", $contract_id);
		
		$user_sid = empty($user_info) ? null : $user_info;
		
		return $user_sid;
	}
}
