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

namespace modules\listing_comments\lib\Email;


class AdminAddCommentEmail extends \modules\main\apps\AdminPanel\EmailTemplateList
{
	protected $id = 'admin_add_comment_email';
	protected $caption = 'Admin add comment';

	public function __construct()
	{
		parent::__construct();
		$this->availableVariables[] = '$admin_site_url';
		$this->availableVariables[] = '$comment';
	}
}
