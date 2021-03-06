<?php
/**
 *
 *    Module: sample_data_iLister_membership_plan v.7.3.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: sample_data_iLister_membership_plan-7.3.0-1
 *    Tag: tags/7.3.0-1@18574, 2015-08-24 13:38:47
 *
 *    This file is part of the 'sample_data_iLister_membership_plan' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\sample_data_iLister_membership_plan;

class Module extends \core\SampleDataModule
{
	protected $name = 'sample_data_iLister_membership_plan';
	protected $caption = 'iLister Membership Plan Sample Data';
	protected $version = '7.3.0-1';
	protected $dependencies = array
	(
		'membership_plan',
		'sample_data_iLister_users',
	);
}
