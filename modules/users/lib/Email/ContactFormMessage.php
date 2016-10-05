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

namespace modules\users\lib\Email;

class ContactFormMessage extends \modules\main\apps\AdminPanel\EmailTemplateList
{
	protected $id = 'contact_form_message';
	protected $caption = 'Contact Form Message';

	public function __construct()
	{
		parent::__construct();
		$this->availableVariables[] = '$FullName';
		$this->availableVariables[] = '$Email';
		$this->availableVariables[] = '$Request';
	}
}
