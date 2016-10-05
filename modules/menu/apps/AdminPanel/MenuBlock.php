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


namespace modules\menu\apps\AdminPanel;

abstract class MenuBlock implements IMenuBlock
{
    protected $items;
    protected $hasActiveItem = false;

    abstract public function getIMenuItemInterfaceName();

    public function __construct()
    {
        $currentUri = \App()->Navigator->getUri();

        $this->items = array();
        $menuItems = new \core\ExtensionPoint($this->getIMenuItemInterfaceName());
        foreach ($menuItems as $menuItem)
        {
            $moduleAndFunctionName = \App()->ModuleManager->getModuleAndFunctionNameByURL($menuItem->getUrl());
            $hasAccess = \App()->AccessControlManager->hasAccess($moduleAndFunctionName['module'], $moduleAndFunctionName['function']);
            if ($hasAccess)
            {
                $item = array
                (
                    'title' => $menuItem->getCaption(),
                    'reference' => $menuItem->getUrl(),
                    'highlight' => $menuItem->getHighlightUrls(),
                );
                $item['active'] = $this->isItemActive($menuItem, $currentUri);
                if ($item['active']) $this->hasActiveItem = true;
                $this->items[] = $item;
            }
        }
    }

    private function isItemActive($menuItem, $currentUri)
    {
        $highlight = $menuItem->getHighlightUrls();
        $highlight[] = $menuItem->getUrl();
        $inputQueryString = \App()->Request->getRequest();
        foreach ($highlight as $h)
        {
            if (is_array($h))
            {
                if ($h['uri'] == $currentUri)
                {
                   $hasInParam  = true;
                   foreach($h['params'] as $param)
                   {
                       if (!in_array($param, $inputQueryString))
                       {
                           $hasInParam = false;
                           break;
                       }
                   }
                   if ($hasInParam) return true;
                }
            }
            else
            {
                $h = str_replace(\App()->SystemSettings['SiteUrl'], '', $h);
                if ($h == $currentUri) return true;
            }
        }
        return false;
    }

    public function hasItems()
    {
        return !empty($this->items);
    }

    public function getItems()
    {
        return $this->items;
    }

    public function hasActiveItem()
    {
        return $this->hasActiveItem;
    }


}
