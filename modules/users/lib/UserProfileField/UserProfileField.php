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

class UserProfileField extends \lib\ORM\Object {

	var $user_group_sid;

	var $field_type;
	
	var $order;

	function setUserGroupSID($user_group_sid) {
		
		$this->user_group_sid = $user_group_sid;
		
	}
	
	function getUserGroupSID() {
		
		return $this->user_group_sid;
		
	}

    function getFieldType()
    {
		return $this->field_type;
	}
	
	function getOrder() {
		
		return $this->order;
		
	}
	function setOrder($order)
	{
		$this->order = $order;
	}
}

?>
