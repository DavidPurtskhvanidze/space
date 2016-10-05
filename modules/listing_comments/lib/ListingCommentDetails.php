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

class ListingCommentDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = 'listing_comments';
	
	protected $detailsInfo = array
	(
		array
		(
			'id' => 'listing_sid',
			'caption' => 'Listing Sid',
			'type' => 'integer',
			'is_required' => true,
		),
		array
		(
			'id' => 'parent_comment_sid',
			'caption' => 'Parent Comment Sid',
			'type' => 'integer',
			'is_required' => false,
			'value' => 0,
		),
		array
		(
			'id' => 'user_sid',
			'caption' => 'User Sid',
			'type' => 'integer',
			'is_required' => true,
		),
		array
		(
			'id' => 'comment',
			'caption' => 'Comment',
			'type' => 'text',
            'maxlength' => '1000',
			'is_required' => true,
		),
		array
		(
			'id' => 'published',
			'caption' => 'Published',
			'type' => 'boolean',
			'is_required' => false,
		),
		array
		(
			'id' => 'posted',
			'caption' => 'Date Posted',
			'type' => 'date',
			'is_required' => false,
		),
		array
		(
			'id'		=> 'last_user_ip',
			'caption'	=> 'Last User IP',
			'type'		=> 'string',
			'is_required' => false,
		),
	);
	
}
