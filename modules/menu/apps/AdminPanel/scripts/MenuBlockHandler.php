<?php
/**
 *
 *    Module: menu v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: menu-7.5.0-1
 *    Tag: tags/7.5.0-1@19799, 2016-06-17 13:20:07
 *
 *    This file is part of the 'menu' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\menu\apps\AdminPanel\scripts;

class MenuBlockHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'menu';
	protected $functionName = 'show_left_menu';
	protected $isPermissionRequired = false;

	public function respond()
	{
	    $menu = array();

        $menuBlocks = new \core\ExtensionPoint('modules\menu\apps\AdminPanel\IMenuBlock');
        foreach ($menuBlocks as $block)
        {
            if (!$block->hasItems()) continue;

            $menu[] = array
            (
                'caption' => $block->getCaption(),
                'active' => $block->hasActiveItem(),
                'items' => $block->getItems(),
            );
        }

        $template_processor = \App()->getTemplateProcessor();
        $template_processor->assign('menu', $menu);
		$template_processor->display('admin_left_menu.tpl');
	}
}
