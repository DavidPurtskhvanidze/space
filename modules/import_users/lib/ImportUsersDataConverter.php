<?php
/**
 *
 *    Module: import_users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_users-7.5.0-1
 *    Tag: tags/7.5.0-1@19788, 2016-06-17 13:19:38
 *
 *    This file is part of the 'import_users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_users\lib;

use lib\DataTransceiver\IDataConverter;

class ImportUsersDataConverter implements IDataConverter
{
    const PASSWORD_FIELD = 'password';
	var $defaultUserGroupSid;
	var $userCreator;
	var $arrayCombiner;
	var $fieldsScheme;
	var $userGroupManager;
	var $userRequiredFieldsDefiner;

	function setUserRequiredFieldsDefiner($userRequiredFieldsDefiner)
	{
		$this->userRequiredFieldsDefiner = $userRequiredFieldsDefiner;
	}
	
	function setDefaultUserGroupSid($defaultUserGroupSid)
	{
		$this->defaultUserGroupSid = $defaultUserGroupSid;
	}
	
	function setUserGroupManager($userGroupManager)
	{
		$this->userGroupManager = $userGroupManager;
	}
	
	function setFieldsScheme($fieldsScheme)
	{
		$this->fieldsScheme = $fieldsScheme;
	}

	function setArrayCombiner($arrayCombiner)
	{
		$this->arrayCombiner = $arrayCombiner;
	}

	function setUserCreator($userCreator)
	{
		$this->userCreator = $userCreator;
	}
		
	function getConverted($userInfo)
	{
		$userInfo = $this->arrayCombiner->combine($this->fieldsScheme, $userInfo);
		if (!empty($userInfo['user_group']))
		{
			$userGroupSid = $this->userGroupManager->getUserGroupSIDByID($userInfo['user_group']);
		}
		if (empty($userGroupSid))
		{
			$userGroupSid = $this->defaultUserGroupSid;
		}

        $propertiesToExclude = ['registration_date', 'user_group_sid', 'user_group', 'group'];

        if (empty($userInfo[self::PASSWORD_FIELD]))
        {
            $propertiesToExclude[] = self::PASSWORD_FIELD;
        }
        else
        {
            $password = $userInfo[self::PASSWORD_FIELD];
            unset($userInfo[self::PASSWORD_FIELD]);
            $userInfo[self::PASSWORD_FIELD]['original'] = $password;
            $userInfo[self::PASSWORD_FIELD]['confirmed'] = $password;
        }

        $user = $this->userCreator->createUser($userInfo, $userGroupSid);

		array_walk($propertiesToExclude, [$user, 'deleteProperty']);
		$this->userRequiredFieldsDefiner->process($user);
		return $user;
	}
}
