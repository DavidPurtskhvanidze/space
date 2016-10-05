<?php
/**
 *
 *    Module: static_content v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: static_content-7.5.0-1
 *    Tag: tags/7.5.0-1@19836, 2016-06-17 13:22:00
 *
 *    This file is part of the 'static_content' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\static_content\apps\SubDomain\scripts;

class ShowStaticContentHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Show Static Content';
	protected $moduleName = 'static_content';
	protected $functionName = 'show_static_content';
	protected $parameters = array('pageid');

	public function respond()
	{
		$handler = new \modules\static_content\apps\FrontEnd\scripts\ShowStaticContentHandler();
        $handler->respond();
	}
}

?>
