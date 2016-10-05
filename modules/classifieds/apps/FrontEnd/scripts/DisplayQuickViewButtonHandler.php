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


namespace modules\classifieds\apps\FrontEnd\scripts;

class DisplayQuickViewButtonHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName	= 'Display Quick View';
	protected $moduleName	= 'classifieds';
	protected $functionName	= 'display_quick_view_button';

	public function respond()
	{
		$listing = \App()->Request['listing'];
		if (!is_null($listing))
		{
			$templateProcessor = \App()->getTemplateProcessor();
			$templateProcessor->assign('listing', $listing);
			$templateProcessor->display('category_templates/display/quick_view_button.tpl');
		}
	}
}
