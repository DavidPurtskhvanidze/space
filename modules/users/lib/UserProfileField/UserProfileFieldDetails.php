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

class UserProfileFieldDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = 'users_profile_fields';
	private $extra_details_info = array();

	public function setExtraDetailsInfo($details_info)
	{
		$this->extra_details_info = $details_info;
	}

	function getDetailsInfo()
	{	
		$common_details_info = array
			   (
				array
				(
					'id'		=> 'id',
					'caption'	=> 'ID', 
					'type'		=> 'string',
					'length'	=> '20',
					'maxlength'	=> '40',
					'table_name'=> 'users_profile_fields',
					'is_required'=> true,
					'is_system' => false,
				),
				array
				(
					'id'		=> 'caption',
					'caption'	=> 'Caption', 
					'type'		=> 'string',
					'length'	=> '20',
					'is_required'=> true,
					'is_system' => false,
				),
				array
				(
					'id'		=> 'type',
					'caption'	=> 'Type',
					'type'		=> 'list',
					'list_values' => array(
											array(
												'id' => 'string',
												'caption' => 'String',
											),
											array(
												'id' => 'text',
												'caption' => 'Text',
											),
											array(
												'id' => 'list',
												'caption' => 'List',
											),
											array(
												'id' => 'boolean',
												'caption' => 'Boolean',
											),
											array(
												'id' => 'tree',
												'caption' => 'Tree',
											),
											array(
												'id' => 'picture',
												'caption' => 'Picture',
											),
											array(
												'id' => 'geo',
												'caption' => 'Known Geographical Location'
											),
										),
					'length'	=> '',
					'is_required'=> true,
					'is_system' => false,
				),
				array
				(
					'id'		=> 'is_required',
					'caption'	=> 'Required',
					'type'		=> 'boolean',
					'length'	=> '',
					'is_required'=> false,
					'is_system' => false,
				),
				
			   );

        return $details_info = array_merge($common_details_info, $this->extra_details_info);
	}

}

?>
