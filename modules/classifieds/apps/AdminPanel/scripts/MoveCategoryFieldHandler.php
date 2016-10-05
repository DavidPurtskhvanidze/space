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


namespace modules\classifieds\apps\AdminPanel\scripts;

class MoveCategoryFieldHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'move_category_field';
	protected $rawOutput = true;

	public function respond()
	{
		$field = \App()->Request['item'];
		if(\App()->Request['action'] == 'sort' && !empty($field))
		{
            \App()->ListingFieldManager->insertOrdersIfNOtExits(\App()->Request['category_sid']);
			\App()->ListingFieldManager->changeFieldOrderForCategory(\App()->Request['category_sid'], $field);
		}
	}
}
