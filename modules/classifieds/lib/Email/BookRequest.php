<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\classifieds\lib\Email;

use modules\main\apps\AdminPanel\EmailTemplateList;

class BookRequest extends EmailTemplateList
{
	protected $id = 'book_request';
	protected $caption = 'Book Request';

	public function __construct()
	{
		parent::__construct();
		$this->availableVariables[] = '$listing';
		$this->availableVariables[] = '$field_sid';
		$this->availableVariables[] = '$sender_name';
		$this->availableVariables[] = '$sender_email';
		$this->availableVariables[] = '$period_start';
		$this->availableVariables[] = '$period_end';
		$this->availableVariables[] = '$comment';
	}
}
