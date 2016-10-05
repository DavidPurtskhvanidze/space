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


namespace modules\main\apps\FrontEnd\scripts;

class Page404Handler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Page 404';
	protected $moduleName = 'main';
	protected $functionName = '404';
	protected $rawOutput = true;

	public function respond()
	{
		header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
		echo "404 Not Found";
	}
}
