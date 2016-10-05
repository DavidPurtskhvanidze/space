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


namespace modules\main\apps\MobileFrontEnd\scripts;

// version 5 wrapper header

class DisplayTemplateHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display Template';
	protected $moduleName = 'main';
	protected $functionName = 'display_template';
	protected $parameters = array('template_file');

	public function respond()
	{
		
// end of version 5 wrapper header


$requestData = \App()->ObjectMother->createRequestReflector();
$action = \App()->ObjectMother->createDisplayTemplateAction($requestData->get('template_file'));
$action->perform();

//  version 5 wrapper footer

	}
}
// end of version 5 wrapper footer
?>
