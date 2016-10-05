<?php
/**
 *
 *    Module: ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19789, 2016-06-17 13:19:41
 *
 *    This file is part of the 'ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\ip_blocklist\lib;

class IpRangeDetails extends \lib\ORM\ObjectDetails
{
	protected $tableName = 'ip_blocklist_blocklist';

	public function getDetailsInfo()
	{
		return array
		(
			array
			(
				'id' => 'ip_range',
				'caption' => 'IP / IP Range',
				'type' => 'string',
				'length' => '31',
				'minimum' => '1',
				'is_required' => true,
				'is_system' => true,
				'save_into_db' => false,
				'validators' => array(new IpRangeValidator()),
			),
			array
			(
				'id' => 'comment',
				'caption' => 'Comment',
				'type' => 'string',
				'length' => '255',
				'is_required' => false,
				'is_system' => true,
			),
		);
	}
}
