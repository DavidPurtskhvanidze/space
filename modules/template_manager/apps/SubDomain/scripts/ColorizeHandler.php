<?php
/**
 *
 *    Module: template_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: template_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19839, 2016-06-17 13:22:09
 *
 *    This file is part of the 'template_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\template_manager\apps\SubDomain\scripts;

class ColorizeHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $rawOutput = true;

	protected $moduleName = 'template_manager';
	protected $functionName = 'colorize';

	public function respond()
	{
        $handler = new \modules\template_manager\apps\FrontEnd\scripts\ColorizeHandler();
        $handler->respond();
	}
}
