<?php
/**
 *
 *    Module: site_pages v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: site_pages-7.5.0-1
 *    Tag: tags/7.5.0-1@19834, 2016-06-17 13:21:53
 *
 *    This file is part of the 'site_pages' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\site_pages\lib;

class PageURLPlugin extends AbstractSmartyPagePlugin implements  \modules\smarty_based_template_processor\lib\IPlugin
{
    protected $pluginTag = 'page_url';

    public function callback($params)
    {
        if (isset($params['id']))
            return \App()->PageRoute->getPageURLById($params['id'], $params['app']);

        if(isset($params['module']) && isset($params['function']))
            return \App()->PageRoute->getSystemPageURL($params['module'], $params['function'], $params['app']);

        return null;
    }
}
