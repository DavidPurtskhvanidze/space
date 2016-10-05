<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\main\lib;


class PasswordGuessing extends \modules\main\apps\AdminPanel\EmailTemplateList
{
	protected $id = 'password_guessing';
	protected $caption = 'Unsuccessful Admin Login Attempt';

	public function __construct()
	{
		parent::__construct();
		$this->availableVariables[] = '$ip';
		$this->availableVariables[] = '$username';
		$this->availableVariables[] = '$limit';
		$this->availableVariables[] = '$Timestamp';
	}
}
