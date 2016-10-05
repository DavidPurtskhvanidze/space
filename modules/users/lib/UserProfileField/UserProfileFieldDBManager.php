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


namespace modules\users\lib\UserProfileField;

class UserProfileFieldDBManager extends \lib\ORM\ObjectDBManager
{
	function getFieldsInfoByUserGroupSID($user_group_sid)
	{
		$fields = \App()->DB->query("SELECT `sid` FROM `users_profile_fields` WHERE `user_group_sid` = ?n OR `user_group_sid` = 0 ORDER BY `user_group_sid`, `order`", $user_group_sid);
		$fields_info = array();
		foreach ($fields as $field) $fields_info[] = $this->getUserProfileFieldInfoBySID($field['sid']);
		return $fields_info;		
	}
	
	function getUserProfileFieldInfoBySID($user_profile_field_sid)
	{
		$field_info = parent::getObjectInfoCached("users_profile_fields", $user_profile_field_sid);
		$this->setComplexFields($field_info);
		return $field_info;
	}

	function setComplexFields(&$field_info)
	{
		if ($field_info['type'] == 'list')
		{
			$field_info['list_values'] = $this->getListValuesBySID($field_info['sid']);

		}
		elseif ($field_info['type'] == 'tree')
		{
			$field_info['tree_values'] = $this->getTreeValuesBySID($field_info['sid']);
			$field_info['tree_depth'] = $this->getTreeDepthBySID($field_info['sid']);
		}
	}

	function getTreeValuesBySID($field_sid) {
		$cacheId = "getTreeValuesBySID_" . $field_sid;
		if(!\App()->MemoryCache->exists($cacheId))
		{
			$field_values = \App()->UserProfileFieldTreeManager->getTreeValuesBySID($field_sid);
			\App()->MemoryCache->set($cacheId, $field_values);
		}
		else
		{
			$field_values = \App()->MemoryCache->get($cacheId);
		}

		return $field_values;
		
	}
	
	function &getTreeDepthBySID($field_sid) {
		$cacheId = "getTreeDepthBySID_" . $field_sid;
		if(!\App()->MemoryCache->exists($cacheId))
		{
			$field_values = \App()->UserProfileFieldTreeManager->getTreeDepthBySID($field_sid);
			\App()->MemoryCache->set($cacheId, $field_values);
		}
		else
		{
			$field_values = \App()->MemoryCache->get($cacheId);
		}

		return $field_values;
	}
	
	function fieldExists($fieldId)
	{
		$sid = \App()->DB->getSingleValue("SELECT sid FROM `users_profile_fields` WHERE id = ?s", $fieldId);
		return $sid > 0;
	}
	
	function getListValuesBySID($user_profile_field_sid)
	{
		$UserProfileFieldListItemManager = new \modules\users\lib\UserProfileField\UserProfileFieldListItemManager();
		$values = $UserProfileFieldListItemManager->getHashedListItemsByFieldSID($user_profile_field_sid);
		$field_values = array();
		foreach ($values as $key => $value) $field_values[] = array('id' => $key, 'caption' => $value);
		return $field_values;
	}

	function saveUserProfileField($user_profile_field)
	{
		$user_group_sid = $user_profile_field->getUserGroupSID();
		if (is_null($user_group_sid)) return false;
		parent::saveObject($user_profile_field);
		if ($user_profile_field->getOrder())	return true;
		$next_order = 1 + \App()->DB->getSingleValue("SELECT MAX(`order`) FROM `users_profile_fields` WHERE user_group_sid = ?n", $user_group_sid);
        return \App()->DB->query("UPDATE `users_profile_fields` SET user_group_sid = ?n, `order` = ?n WHERE sid = ?n", $user_group_sid, $next_order, $user_profile_field->getSID());
		return false;
	}
	
	function deleteUserProfileFieldInfo($user_profile_field_sid)
	{
		$field_info = $this->getUserProfileFieldInfoBySID($user_profile_field_sid);
		if (!strcasecmp("list", $field_info['type'])) \App()->DB->query("DELETE FROM `users_profile_field_list` WHERE field_sid = ?n" , $user_profile_field_sid);
		return parent::deleteObjectInfoFromDB('users_profile_fields', $user_profile_field_sid);
	}
}
