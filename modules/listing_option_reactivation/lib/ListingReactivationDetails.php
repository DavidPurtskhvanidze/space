<?php
/**
 *
 *    Module: listing_option_reactivation v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_option_reactivation-7.5.0-1
 *    Tag: tags/7.5.0-1@19794, 2016-06-17 13:19:54
 *
 *    This file is part of the 'listing_option_reactivation' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_option_reactivation\lib;

class ListingReactivationDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = 'listing_option_reactivation_reactivations';
	
	public static $system_details = array
											(
												array
												(
													'id'			=> 'user_sid',
													'caption'		=> 'User Sid',
													'type'			=> 'integer',
												),
												array
												(
													'id'			=> 'listing_sid',
													'caption'		=> 'Listing Sid',
													'type'			=> 'integer',
												),
												array
												(
													'id'			=> 'package_sid',
													'caption'		=> 'Package Sid',
													'type'			=> 'integer',
												),
												array
												(
													'id'			=> 'package_info',
													'caption'		=> 'Package Info',
													'type'			=> 'array',
												),
												array
												(
													'id'			=> 'options_to_activate',
													'caption'		=> 'Options To Activate',
													'type'			=> 'array',
												),
												array
												(
													'id'			=> 'activated',
													'caption'		=> 'Option Activated',
													'type'			=> 'boolean',
												),
											);
}
