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

class UserManager extends \lib\ORM\ObjectManager implements \core\IService
{
	public function init()
	{
		$this->dbManager = new \modules\users\lib\User\UserDBManager();
	}
	
	public function &createUser($userData, $groupSid, $remember = true)
	{
        if (isset($userData['sid']))
        {
            if (!$remember && !isset($this->users[$userData['sid']]))
            {
                $user = $this->_createUser($userData, $groupSid);
                $user->setSid($userData['sid']);
                $this->users[$userData['sid']] = $user;
                $user = $this->users[$userData['sid']];
            }
            else
            {
                $user = $this->_createUser($userData, $groupSid);
                $user->setSid($userData['sid']);
            }
        }
        else
        {
            $user = $this->_createUser($userData, $groupSid);
        }

		return $user;
	}

    private function _createUser($userData, $groupSid)
    {
        $user = new User();
        $user->setUserGroupSID($groupSid);
        $user->setDetails($this->createUserDetails($groupSid));
        $user->incorporateData($userData);
        $user->addProperty(array('id' => 'group', 'type' => 'object', 'value' => \App()->UserGroupManager->getObjectBySID($user->getUserGroupSid()),'save_into_db'=> false));
        return $user;
    }

    private $users = [];

    function getObjectBySID($userSid)
    {
        if (!isset($this->users[$userSid]))
        {
            $userInfo = $this->getUserInfoBySID($userSid);
            if (is_null($userInfo)) return null;
            $user = $this->createUser($userInfo, $userInfo['user_group_sid']);
            $this->users[$userSid] = $user;
        }

        return $this->users[$userSid];
    }
	
	private function createUserDetails($userGroupSid)
	{
		$details = new UserDetails();
		$details->setDetailsInfo($this->getDetails($userGroupSid));
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildProperties();
		return $details;
	}
	
	function getDetails($user_group_sid)
	{
		$extra_details = \App()->UserProfileFieldManager->getFieldsInfoByUserGroupSID($user_group_sid);
		foreach ($extra_details as $key => $value) $extra_details[$key]['is_system'] = false;
		return array_merge(UserDetails::$systemDetails, $extra_details);
	}
	
	function &getCurrentUser()
	{
		$userInfo = $this->getCurrentUserInfo();
		$user = null;
		if (!is_null($userInfo))
		{
			$user = $this->createUser($userInfo, $userInfo['user_group_sid']);
			$user->setSID($userInfo['sid']);
		}
		return $user;
	}
	
	public function getUserInfoBySID($userSid)
	{
		return $this->dbManager->getUserInfoBySID($userSid);
	}
	
	function isUserActiveBySID($userSid)
	{
		$active = \App()->DB->getSingleValue("SELECT active FROM `users_users` WHERE sid = ?n", $userSid);
		return !empty($active) ? $active : null;
	}
	
	public function saveUser(&$user)
	{
		if(!$user->isSavedInDB())
		{
			$user->createActivationKey();
			$user->createVerificationKey();
		}
		$this->dbManager->saveUser($user);
		if ($this->isUserLoggedIn()) $this->updateCurrentUserSession();
		return true;
	}
	
	function getAllUsersInfo()
	{
		return $this->dbManager->getAllUsersInfo();
	}

	public function getAllUsersSidsAndNames()
	{
		$usersInfo = $this->getAllUsersInfo();
		$result = array();
		foreach ($usersInfo as $userInfo)
		{
			$result[] = array('id' => $userInfo['sid'], 'caption' => $userInfo['username']);
		}
		return $result;
	}

	function getUsersNumberByGroupSID($user_group_sid)
	{
		$count = \App()->DB->getSingleValue("SELECT COUNT(*) FROM `users_users` WHERE user_group_sid = ?n", $user_group_sid);
		return $count;
	}
	
	function getUserSIDsByUserGroupSID($user_group_sid)
	{
		$sql_result = \App()->DB->query("SELECT `sid` FROM `users_users` WHERE `user_group_sid`=?n", $user_group_sid);
		return $this->_getUserSIDsFromRawSIDInfo($sql_result);
	}

	function getUserSIDsByMembershipPlanSID($membership_plan_sid)
	{

		$sql_result = \App()->DB->query(
			"SELECT `users_users`.`sid` FROM `users_users`, `membership_plan_contracts` WHERE `users_users`.`contract_sid`=`membership_plan_contracts`.`sid` AND `membership_plan_contracts`.`membership_plan_sid`=?n",
			$membership_plan_sid);
		return $this->_getUserSIDsFromRawSIDInfo($sql_result);
	}

	function getMembershipPlanSIDByUserSID($userSid)
	{
		$sql_result = \App()->DB->getSingleValue("SELECT `membership_plan_contracts`.`membership_plan_sid` FROM `users_users`, `membership_plan_contracts`
								 WHERE `users_users`.`contract_sid`=`membership_plan_contracts`.`sid` AND `users_users`.`sid`=?n",
								 $userSid);
		return !empty($sql_result) ? $sql_result : null;
	}

	function _getUserSIDsFromRawSIDInfo($raw_sid_info) 
	{
		$result = array();
		foreach($raw_sid_info as $found_sid_info)
			$result[] = $found_sid_info['sid'];
		return $result;
	}
	
	function deleteUserBySid($userSid)
	{
		return $this->dbManager->deleteUserBySid($userSid);
	}
	
	function activateUserByUserName($username)
	{
		return $this->dbManager->activateUserByUserName($username);
	}
	
	function deactivateUserByUserName($username)
	{
		return $this->dbManager->deactivateUserByUserName($username);
	}
	
	public function getUserInfoByUserName($username)
	{
		return $this->dbManager->getUserInfoByUserName($username);
	}

	public function getUserInfoByEmail($username)
	{
		return $this->dbManager->getUserInfoByEmail($username);
	}

	function canLogin($username, $password, &$errors)
	{
		$user_exists_by_username = \App()->DB->getSingleValue("SELECT count(*) FROM `users_users` WHERE `username` = ?s", $username);
		
		if($user_exists_by_username) 
		{
			$user_exists_by_password = \App()->DB->getSingleValue("SELECT count(*) FROM `users_users` WHERE `username` = ?s AND `password` = PASSWORD(?s) AND `password` != '' ", $username, $password);
			if($user_exists_by_password)
			{
				return true;
			}
			else 
			{
				$errors['INVALID_PASSWORD'] = 1;
				return false;
			}
		} 
		else 
		{
			$errors['NO_SUCH_USER'] = 1;
			return false;
		}
	}

	function canLoginViaEmail($email, $password, &$errors)
	{
		$user_exists_by_email = \App()->DB->getSingleValue("SELECT count(*) FROM `users_users` WHERE `email` = ?s", $email);
		
		if($user_exists_by_email) 
		{
			$user_exists_by_password = \App()->DB->getSingleValue("SELECT count(*) FROM `users_users` WHERE `email` = ?s AND `password` = PASSWORD(?s) AND `password` != '' ", $email, $password);
			if($user_exists_by_password)
			{
				return true;
			}
			else 
			{
				$errors['INVALID_PASSWORD'] = 1;
				return false;
			}
		} 
		else 
		{
			$errors['NO_SUCH_USER'] = 1;
			return false;
		}
	}
	
	public function getCurrentUserSID()
	{
		$userInfo = $this->getCurrentUserInfo();
		
		if (!is_null($userInfo))
		{
			return $userInfo['sid'];
		}
		else
		{
			return null;
		}
	}

	function getUserNameByUserSID($userSid)
	{
		return $this->dbManager->getUserNameByUserSID($userSid);
	}

	function getUserSIDbyUsername($username)
	{
		$userInfo = $this->getUserInfoByUserName($username);

		if (!empty($userInfo))
		{
			return $userInfo['sid'];
		}
		else
			return null;
	}

	function getUserSIDsLikeUsername($username)
	{
		return $this->dbManager->getUserSIDsLikeUsername($username);
	}

	function changeUserPassword($userSid, $password)
	{
		return \App()->DB->query("UPDATE `users_users` SET `password`=PASSWORD(?s) WHERE `sid`=?s", $password, $userSid);
	}
	
	function getUserSIDByContractID($contractId)
	{
		return $this->dbManager->getUserSIDByContractID($contractId);
	}
	
	function saveUserSessionKey($sessionKey, $userSid)
	{
		\App()->DB->query("INSERT INTO `users_sessions` SET `session_key` = ?s, `user_sid` = ?n, `remote_ip` = ?s, `user_agent` = ?s, `start` = UNIX_TIMESTAMP()", $sessionKey, $userSid, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
	}
	
	function removeUserSessionKey($sessionKey)
	{
		\App()->DB->query("DELETE FROM `users_sessions` WHERE `session_key` = ?s", $sessionKey);
	}
	
	function getUserSIDBySessionKey($sessionKey)
	{
		$userSid = \App()->DB->getSingleValue("SELECT user_sid FROM `users_sessions` WHERE `session_key` = ?s", $sessionKey);
		if (empty($userSid)) return null;
		return $userSid;
	}

    function createTemplateStructureForUser($user)
	{
		$userInfo = parent::getObjectInfo($user);
		$user_group_info = \App()->UserGroupManager->getUserGroupInfoBySID($userInfo['system']['user_group_sid']);
		$structure = array
		(
            'id'				=> $userInfo['system']['id'],
			'user_name'			=> $userInfo['system']['username'],
			'email'				=> $userInfo['system']['email'],
			'group'				=> array
									(
										'id' 		=> $user_group_info['id'],
										'caption'	=> $user_group_info['name'],
									),
			'registration_date'	=> $userInfo['system']['registration_date'],
		);

		return array_merge($structure, $userInfo['user_defined']);
	}

    function createTemplateStructureForCurrentUser()
	{
		$user = $this->getCurrentUser();
		return $this->createTemplateStructureForUser($user);
	}
	
	function isUserTrusted($userSid)
	{
		$res = \App()->DB->query("SELECT * FROM `users_users` WHERE `sid` = ?n AND `trusted_user` = 1", $userSid);
		return !empty($res);
	}
	
	public function makeUsersTrusted($userSids)
	{
		return \App()->DB->query("UPDATE `users_users` SET `trusted_user` = 1 WHERE `sid` IN (?l)", $userSids);
	}

	public function makeUsersUntrusted($userSids)
	{
		return \App()->DB->query("UPDATE `users_users` SET `trusted_user` = 0 WHERE `sid` IN (?l)", $userSids);
	}

	function makeTrusted($userSid)
	{
		return \App()->DB->query("UPDATE `users_users` SET `trusted_user` = 1 WHERE `sid` =?n", $userSid);
	}

	function makeUntrusted($userSid)
	{
		return \App()->DB->query("UPDATE `users_users` SET `trusted_user` = 0 WHERE `sid` =?n", $userSid);
	}
	
	function getUserProperties($user_group_sid = null)
	{		
		$systemProperties = array
		(
			array('id' => 'id', 'caption' => 'ID'),
			array('id' => 'username', 'caption' => 'Username'),
			array('id' => 'email', 'caption' => 'E-mail'),
			array('id' => 'user_group', 'caption' => 'User Group'),
			array('id' => 'registration_date', 'caption' => 'Registration Date'),
			array('id' => 'active', 'caption' => 'Is Active'),
		);
		$userDefinedProperties = \App()->UserProfileFieldManager->getFieldsInfoByUserGroupSID($user_group_sid);
				
		return array_merge($systemProperties, $userDefinedProperties);
	}
	
	function getAllUserSIDs()
	{
		$sql_result = \App()->DB->query("SELECT `sid` FROM `users_users`");
		return $this->_getUserSIDsFromRawSIDInfo($sql_result);
	}
	
	function doesUserExistBySid($userSid)
	{
		return !is_null($this->getUserInfoBySID($userSid));
	}
	
	function login($username, $password, $keep_signed, &$errors)
	{
		if (!filter_var($username, FILTER_VALIDATE_EMAIL) && $this->canLogin($username, $password, $errors))
		{
			$userInfo = $this->getUserInfoByUserName($username);
			if (!$userInfo['active'])
			{
				$errors['USER_NOT_ACTIVE'] = 1;
				return false;
			}			
			if ($keep_signed) $this->keepUserSignedIn($userInfo);
			$this->setSessionForUser($userInfo);
			return true;
		}
		else if (filter_var($username, FILTER_VALIDATE_EMAIL) && $this->canLoginViaEmail($username, $password, $errors))
		{
			$userInfo = $this->getUserInfoByEmail($username);
			if (!$userInfo['active'])
			{
				$errors['USER_NOT_ACTIVE'] = 1;
				return false;
			}			
			if ($keep_signed) $this->keepUserSignedIn($userInfo);
			$this->setSessionForUser($userInfo);
			return true;
		} 
		else
		{
			return false;
		}
	}

	function keepUserSignedIn($userInfo)
	{
		$sessionKey = $this->generateSessionKey();
		$this->setKeepCookieForUser($sessionKey);
		$this->saveUserSessionKey($sessionKey, $userInfo['sid']);
	}
	
	function generateSessionKey($length = 32)
	{
		$s = "abcdefghijklmnopqrstuvwxyz0123456789";
		$len = strlen($s);
		$key = '';
		for ($i = 0; $i < $length; $i++)
		{
			$key .= $s[mt_rand(0, $len - 1)];
		}
		return $key;
	}
	
	public function setSessionForUser($userInfo)
	{
		\App()->Session->setValue('current_user', $userInfo);
	}
	
	function setKeepCookieForUser($sessionKey, $prolong_cookie = true)
	{
		if ($prolong_cookie)
		{
			\App()->Cookie->setCookie('session_key', $sessionKey, 30);
		}
		else
		{
			\App()->Cookie->setCookie('session_key', $sessionKey, -30);
		}
	}
	
	function updateCurrentUserSession() 
	{
		if ($this->isUserLoggedIn())
		{
			$currentUser = \App()->Session->getValue('current_user');
			$this->setSessionForUser($this->getUserInfoByUserName($currentUser['username']));
		}
	}
	
	function isUserLoggedIn()
	{
		if (!is_null(\App()->Session->getValue('current_user')))
		{
			return true;
		}
		return $this->checkForKeep();
	}
	
	function checkForKeep()
	{
		if (\App()->Cookie->getCookie('session_key'))
		{
			$userSid = $this->getUserSIDBySessionKey(\App()->Cookie->getCookie('session_key'));
			if (!is_null($userSid))
			{
				$this->setSessionForUser($this->getUserInfoBySID($userSid));
				$this->setKeepCookieForUser(\App()->Cookie->getCookie('session_key'));
				return true;
			}
		}
		return false;
	}
	
	function logout()
	{
		if (\App()->Cookie->getCookie('session_key'))
		{
			$sessionKey = \App()->Cookie->getCookie('session_key');
			$this->removeUserSessionKey($sessionKey);
			$this->setKeepCookieForUser($sessionKey, false);
		}
		\App()->Session->setValue('current_user', null);
	}
	
	function getCurrentUserInfo()
	{
		if (is_null(\App()->Session->getValue('current_user')))
		{
			if (\App()->Cookie->getCookie('session_key'))
			{
				$userSid = $this->getUserSIDBySessionKey(\App()->Cookie->getCookie('session_key'));
				if (!is_null($userSid))
				{
					$this->setSessionForUser($this->getUserInfoBySID($userSid));
					$this->setKeepCookieForUser(\App()->Cookie->getCookie('session_key'));
				}
			}
		}
		return \App()->Session->getValue('current_user');
	}

	public function getActiveUsersCount()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `users_users` WHERE active = 1");
	}

	public function getUsersWithListingsCount()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(DISTINCT `users_users`.sid) FROM `users_users` JOIN `classifieds_listings` ON `users_users`.sid = `classifieds_listings`.user_sid");
	}

	public function getUsersWaitingApprovalCount()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `users_users` WHERE active = 0");
	}

	public function getUsersCountForLastDay()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `users_users` WHERE registration_date >= CURDATE() - INTERVAL 1 DAY");
	}

	public function getUsersCountForLastWeek()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `users_users` WHERE registration_date >= CURDATE() - INTERVAL 7 DAY");
	}

	public function getUsersCountForLastMonth()
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `users_users` WHERE registration_date >= CURDATE() - INTERVAL 1 MONTH");
	}

	public function getUsersCountRegisteredBefore($date)
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM `users_users` WHERE registration_date <= ?s", $date);
	}

	public function changeUserGroupByUserSid($userSid, $groupId)
	{
		if (!($userInfo = $this->getUserInfoBySID($userSid)))
		{
			throw new \Exception('USER_NOT_FOUND');
		}
		if (!($groupSid = \App()->UserGroupManager->getUserGroupSIDByID($groupId)))
		{
			throw new \Exception('USERGROUP_NOT_FOUND');
		}
		if (!empty($userInfo['contract_sid']))
		{
			$userContract = \App()->ContractManager->getContractBySID($userInfo['contract_sid']);
			$GroupMembershipPlans = \App()->MembershipPlanManager->getAllMembershipPlanSIDsByUserGroupSID($groupSid);
			if (!in_array($userContract->getMembershipPlanSID(), $GroupMembershipPlans))
			{
				\App()->ContractManager->deleteContract($userInfo['contract_sid']);
				$userInfo['contract_sid'] = 0;
			}
		}
		\App()->DB->query("UPDATE `users_users` SET `user_group_sid`=?n, `contract_sid`=?n WHERE `sid`=?n", $groupSid, $userInfo['contract_sid'], $userSid);
	}

	public function sendUserActivationLetter($userSid)
	{
		$userInfo = $this->getUserInfoBySID($userSid);

		return \App()->EmailService->send($userInfo['email'], 'email_template:activate_account', array(
			'user' => $userInfo,
		));
	}

	public function sendUserPasswordChangeLetter($userSid)
	{
		$userInfo = $this->getUserInfoBySID($userSid);
		return \App()->EmailService->send($userInfo['email'], 'email_template:user_change_password', array('user' => $userInfo));
	}

	public function requireLogin()
	{
		\App()->ErrorMessages->addMessage('USER_NOT_LOGGED_IN', array(), 'users');
		$tp = \App()->getTemplateProcessor();
		$tp->display("users^require_login.tpl");
	}
	
	public function fetchAutocompleteOptionsForUsername($keyword, $maxRows)
	{
		$keyword = "%{$keyword}%";
		$dataSet = \App()->DB->query('SELECT `username`, `FirstName`, `LastName` FROM `users_users` WHERE(`username` LIKE ?s) ORDER BY `username` ASC LIMIT ?n', $keyword, $maxRows);
		if (empty($dataSet))
		{
			$dataSet = array();
		}
		
		$result = array();
		foreach($dataSet as $record)
		{
			$node = array(
				'value' => $record['username'],
				'label' => $record['username'],
			);
			if (!empty($record['FirstName']) || !empty($record['LastName']))
			{
				$node['label'] .= ' - ' . $record['FirstName'] . ' ' . $record['LastName'];
			}
			$result[] = $node;
		}
		return $result;
	}
	
	public function fetchAutocompleteOptionsForEmail($keyword, $maxRows)
	{
		$keyword = "%{$keyword}%";
		$dataSet = \App()->DB->query('SELECT `email`, `FirstName`, `LastName` FROM `users_users` WHERE(`email` LIKE ?s) ORDER BY `email` ASC LIMIT ?n', $keyword, $maxRows);
		if (empty($dataSet))
		{
			$dataSet = array();
		}
		
		$result = array();
		foreach($dataSet as $record)
		{
			$node = array(
				'value' => $record['email'],
				'label' => $record['email'],
			);
			if (!empty($record['FirstName']) || !empty($record['LastName']))
			{
				$node['label'] .= ' - ' . $record['FirstName'] . ' ' . $record['LastName'];
			}
			$result[] = $node;
		}
		
		return $result;
	}
}
