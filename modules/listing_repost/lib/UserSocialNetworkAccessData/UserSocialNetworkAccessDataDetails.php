<?php
/**
 *
 *    Module: listing_repost v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_repost-7.5.0-1
 *    Tag: tags/7.5.0-1@19795, 2016-06-17 13:19:57
 *
 *    This file is part of the 'listing_repost' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_repost\lib\UserSocialNetworkAccessData;

class UserSocialNetworkAccessDataDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = 'listing_repost_social_network_service_data';
	
	protected $detailsInfo = array(
		
				array
				(
					'id'		=> 'user_sid',
					'caption'	=> 'User SID',
					'type'		=> 'integer',
					'length'	=> '1',
					'is_required'=> true,
					'is_system'	=> true,
				),
				array
				(
					'id'		=> 'provider_id',
					'caption'	=> 'Provider ID',
					'type'		=> 'string',
					'length'	=> '255',
					'is_required'=> true,
					'is_system'	=> true,
				),
				array
				(
					'id'		=> 'access_token',
					'caption'	=> 'Access Data',
					'type'		=> 'array',
					'is_required'=> true,
					'is_system'	=> true,
				),
				array
				(
					'id'		=> 'enabled',
					'caption'	=> 'Status',
					'type'		=> 'boolean',
					'length'	=> '1',
					'is_required'=> true,
					'is_system'	=> true,
				),
		);
}
