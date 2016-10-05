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

class UserDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = 'users_users';
	
	public static $systemDetails = array(
												array
												(
													'id'			=> 'sid',
													'caption'		=> 'Id',
													'type'			=> 'integer',
													'length'		=> '6',
													'is_required'	=> false,
													'is_system'		=> true,
													'order'			=> null,
												),
												array
												(
													'id'		=> 'username',
													'caption'	=> 'Username', 
													'type'		=> 'unique_string',
													'table_name' => 'users_users',
													'length'	=> '20',
													'is_required'=> true,
													'is_system'=> true,
													'autocomplete_service_name' => 'UserManager',
													'autocomplete_method_name' => 'Username'
												),
												array
												(
													'id'		=> 'password',
													'caption'	=> 'Password',
													'type'		=> 'password',
													'length'	=> '20',
													'is_required'=> true,
													'is_system'=> true,
												),
												array
												(
													'id'		=> 'email',
													'caption'	=> 'E-mail',
													'type'		=> 'unique_email',
													'table_name' => 'users_users',
													'length'	=> '20',
													'is_required'=> true,
													'is_system'=> true,
													'autocomplete_service_name' => 'UserManager',
													'autocomplete_method_name' => 'Email',
												),
												
												array
												(
													'id'		=> 'active',
													'caption'	=> 'Active Status',
													'type'		=> 'boolean',
													'length'	=> '1',
													'is_required'=> false,
													'is_system'=> true,
												),
												
												array
												(
													'id'		=> 'trusted_user',
													'caption'	=> 'Trusted User',
													'type'		=> 'boolean',
													'length'	=> '1',
													'is_required'=> false,
													'is_system'=> true,
												),
												array
												(
													'id'		=> 'registration_date',
													'caption'	=> 'Registration Date',
													'type'		=> 'date',
													'length'	=> '1',
													'is_required'=> false,
													'is_system'=> true,
													'save_into_db' => false,
												),
												array
												(
													'id'		=> 'user_group_sid',
													'caption'	=> 'User Group Sid',
													'type'		=> 'integer',
													'length'	=> '1',
													'is_required'=> false,
													'is_system'=> true,
												),												
												array
												(
													'id'		=> 'user_group',
													'caption'	=> 'User Group',
													'type'		=> 'integer',
													'length'	=> '1',
													'is_required'=> false,
													'is_system'=> true,
													'table_name' => 'users_user_groups',
													'column_name' => 'id',
													'join_condition' => array('key_column' => 'user_group_sid', 'foriegn_column' => 'sid')
												),												
										);
	
}

?>
