<?php
/**
 *
 *    Module: listing_comments v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_comments-7.5.0-1
 *    Tag: tags/7.5.0-1@19790, 2016-06-17 13:19:43
 *
 *    This file is part of the 'listing_comments' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_comments\lib;

class ListingComment extends \lib\ORM\Object
{
	public function setPublic($v)
	{
		$this->setPropertyValue('published', $v);
	}
	public function setListingSid($v)
	{
		$this->setPropertyValue('listing_sid', $v);
	}
	public function setParentCommentSid($v)
	{
		$this->setPropertyValue('parent_comment_sid', $v);
	}
	public function getParentCommentSid()
	{
		return $this->getPropertyValue('parent_comment_sid');
	}
	public function getListingSid()
	{
		return $this->getPropertyValue('listing_sid');
	}
	public function setUserSid($v)
	{
		$this->setPropertyValue('user_sid', $v);
	}
	public function getUserSid()
	{
		return $this->getPropertyValue('user_sid');
	}
   	public function setLastUserIp($v) {
		$this->setPropertyValue('last_user_ip', $v);
	}
	public function addUsernameProperty($username = null)
	{
		$this->addProperty
		(
			array(
				'id' => 'username',
				'type' => 'string',
				'value' => $username,
				'is_system' => true,
				'table_name' => 'users_users',
				'column_name' => 'username',
				'join_condition' => array('key_column' => 'user_sid', 'foriegn_column' => 'sid'),
				'autocomplete_service_name' => 'UserManager',
				'autocomplete_method_name' => 'Username'
			)
		);
	}
	public function addListingIDProperty($id = null)
	{
		$this->addProperty
		(
			array(
				'id' => 'listing_id',
				'type' => 'string',
				'is_system' => true,
				'caption' => 'ID',
				'value' => $id,
				'column_name' => 'listing_sid',
				'save_into_db' => false,
			)
		);
	}
}
