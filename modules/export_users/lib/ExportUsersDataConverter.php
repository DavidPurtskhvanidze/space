<?php
/**
 *
 *    Module: export_users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_users-7.5.0-1
 *    Tag: tags/7.5.0-1@19780, 2016-06-17 13:19:18
 *
 *    This file is part of the 'export_users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_users\lib;

class ExportUsersDataConverter implements \lib\DataTransceiver\IDataConverter
{
	var $fieldsScheme;
	private $userManager;
	private $userGroupManager;
    public $geoFieldsIds;

	public function setUserManager($userManager)
	{
		$this->userManager = $userManager;
	}
	public function setUserGroupManager($userGroupManager)
	{
		$this->userGroupManager = $userGroupManager;
	}

	function setFieldsScheme($fieldsScheme)
	{
		$this->fieldsScheme = $fieldsScheme;
	}

    function setGeoFieldsIds($geoField)
    {
        $this->geoFieldsIds = $geoField;
    }

	function getConverted($user)
	{
        $this->addUserProperties($user);
        $convertedData = array();
        foreach($this->fieldsScheme as $field)
        {
            $convertedData[$field] = $user->getPropertyDisplayValue($field);
            if(in_array($field, $this->geoFieldsIds)) $convertedData[$field] = (string) $convertedData[$field];
        }
        return $convertedData;
	}
	
	function addUserProperties(&$user)
	{
		$user_group_info = $this->userGroupManager->getUserGroupInfoBySID($user->getUserGroupSID());
		$user_group_id = $user_group_info['id'];
		$user_info = $this->userManager->getUserInfoBySID($user->getSID());
		$registration_date = $user_info['registration_date'];

	    $user->addProperty(array(
							'id'	=> 'id',
							'type'	=> 'string',
							'value'	=> $user->getSID(),
							));
		$user->addProperty(array(
							'id'	=> 'user_group',
							'type'	=> 'string',
							'value'	=> $user_group_id,
							));
		$user->addProperty(array(
							'id'	=> 'registration_date',
							'type'	=> 'date',
							'value'	=> $registration_date,
							));
		
	}
}
