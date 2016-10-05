<?php
/**
 *
 *    Module: form_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: form_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19783, 2016-06-17 13:19:26
 *
 *    This file is part of the 'form_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\form_manager\apps\MobileFrontEnd\scripts;

class SearchFormHandler extends \modules\classifieds\apps\MobileFrontEnd\scripts\SearchFormHandler
{
	protected $moduleName = 'form_manager';
	protected $functionName = 'display_search_form';
	protected $parameters = array('form_id', 'form_template');

	public function respond()
	{
		$handler = new \modules\form_manager\apps\FrontEnd\scripts\SearchFormHandler();
        $handler->respond();
	}
}
