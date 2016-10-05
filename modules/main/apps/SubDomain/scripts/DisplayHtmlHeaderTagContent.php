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


namespace modules\main\apps\SubDomain\scripts;

class DisplayHtmlHeaderTagContent extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Display HTML HEAADER Tag Content';
	protected $moduleName = 'main';
	protected $functionName = 'display_html_header_tag_content';

	public function respond()
	{
		$displayers = new \core\ExtensionPoint('modules\main\apps\SubDomain\IHtmlHeaderTagContentDisplayer');
		foreach ($displayers as $displayer)
		{
			$displayer->display();
		}
	}
}
