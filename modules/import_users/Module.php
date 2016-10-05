<?php
/**
 *
 *    Module: import_users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_users-7.5.0-1
 *    Tag: tags/7.5.0-1@19788, 2016-06-17 13:19:38
 *
 *    This file is part of the 'import_users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_users;

class Module extends \core\Module
{
	protected $name = 'import_users';
	protected $caption = 'Import Users';
	protected $version = '7.5.0-1';
	protected $dependencies = array
	(
		'users',
	);
}
