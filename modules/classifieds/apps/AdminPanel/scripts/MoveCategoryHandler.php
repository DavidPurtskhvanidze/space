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

class MoveCategoryHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'move_category';
	protected $rawOutput = true;

	public function respond()
	{
		$order = \App()->Request['item'];
		if(\App()->Request['action'] == 'sort' && !empty($order))
		{
			$object_replacer = \App()->ObjectMother->createCategoriesReplacer($order,\App()->Request['parentValue']);
			$object_replacer->update();
		}
	}
}
