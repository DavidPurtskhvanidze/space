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

class UserGroupDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = 'users_user_groups';

	function getDetailsInfo()
	{
		$detailsInfo = array
		(
			array
			(
				'id' => 'id',
				'caption' => 'ID',
				'type' => 'unique_string',
				'length' => '20',
				'table_name' => 'users_user_groups',
				'is_required' => true,
				'is_system' => true,
			),
			array
			(
				'id' => 'name',
				'caption' => 'Group name',
				'type' => 'string',
				'length' => '20',
				'table_name' => 'users_user_groups',
				'is_required' => true,
				'is_system' => false,
			),
			array
			(
				'id' => 'active',
				'caption' => 'Is Group Active',
				'type' => 'boolean',
				'length' => '',
				'table_name' => 'users_user_groups',
				'is_required' => false,
				'is_system' => false,
			),
			array
			(
				'id' => 'reg_form_template',
				'caption' => 'Registration form template',
				'type' => 'string',
				'length' => '',
				'is_required' => false,
				'is_system' => false,
			),
			array
			(
				'id' => 'description',
				'caption' => 'Description',
				'type' => 'text',
				'length' => '',
				'is_required' => false,
				'is_system' => false,
			),
			array
			(
				'id' => 'immediate_activation',
				'caption' => 'Immediate Users Activation',
				'type' => 'boolean',
				'length' => '',
				'is_required' => false,
				'is_system' => false,
			),
			array
			(
				'id' => 'user_menu_template',
				'caption' => 'User Menu Template',
				'type' => 'string',
				'length' => '',
				'is_required' => false,
				'is_system' => false,
			),
			array
			(
				'id' => 'make_user_trusted',
				'caption' => 'Make user trusted after registration',
				'type' => 'boolean',
				'length' => '',
				'is_required' => false,
				'is_system' => false,
			),
		);

		$extraDetails = new \core\ExtensionPoint('modules\users\lib\UserGroup\IUserGroupExtraDetail');
		/**
		 * @var IUserGroupExtraDetail $extraDetail
		 */
		foreach ($extraDetails as $extraDetail)
		{
			$detailInfo = array
			(
				'id' => $extraDetail->getId(),
				'caption' => $extraDetail->getCaption(),
				'type' => $extraDetail->getType(),
			);
			$detailsInfo[] = $detailInfo;
		}
		return $detailsInfo;
	}
}
