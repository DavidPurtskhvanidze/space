<?php
/**
 *
 *    Module: membership_plan v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: membership_plan-7.5.0-1
 *    Tag: tags/7.5.0-1@19798, 2016-06-17 13:20:05
 *
 *    This file is part of the 'membership_plan' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\membership_plan\apps\AdminPanel\scripts;

class SetPackageDisplayOrderHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'membership_plan';
	protected $functionName = 'set_package_display_order';
	protected $rawOutput = true;

	public function respond()
	{
		$newOrder = \App()->Request['sortingOrder'];
		if(\App()->Request['action'] == 'sort' && !empty($newOrder))
		{
			\App()->PackageManager->setPackageDisplayOrder(\App()->Request['membership_plan_sid'], $newOrder);
		}
	}
}
