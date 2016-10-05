<?php
/**
 *
 *    Module: membership_plan v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: membership_plan-7.5.0-1
 *    Tag: tags/7.5.0-1@19798, 2016-06-17 13:20:05
 *
 *    This file is part of the 'membership_plan' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\membership_plan\lib\Email;

class UserContractExtended extends \modules\main\apps\AdminPanel\EmailTemplateList
{
	protected $id = 'user_contract_extended';
	protected $caption = 'User Contract Extended';

	public function __construct()
	{
		parent::__construct();
		$this->availableVariables[] = '$user';
		$this->availableVariables[] = '$numberOfDays';
	}
}
