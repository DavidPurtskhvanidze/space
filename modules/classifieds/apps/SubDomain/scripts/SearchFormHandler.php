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


namespace modules\classifieds\apps\SubDomain\scripts;

class SearchFormHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Search Form';
	protected $moduleName = 'classifieds';
	protected $functionName = 'search_form';
	protected $parameters = array('category_id', 'form_template');

	public function respond()
	{
		$_REQUEST['user_sid']['equal'] = \App()->Dealer['user_sid'];
		$handler = new \modules\classifieds\apps\FrontEnd\scripts\SearchFormHandler();
		$handler->respond();
	}
}
