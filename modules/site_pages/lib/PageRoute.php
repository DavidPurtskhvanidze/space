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

use modules\classifieds\lib\PageURIException;

class PageRoute implements \core\IService
{
    private $pageURLs = array();
    private $applicationsSitePath = array();
    private $systemUrlBase;

    public function init()
    {
        $pages = \App()->DB->query('SELECT `id`, `uri`, `application_id`, `no_index` FROM `site_pages_pages` WHERE `application_id` IN (?l)', \App()->getAppIDs());

        $applications = [];
        foreach($pages as $page)
        {
            $applications[$page['application_id']] = $page['application_id'];
            $this->pageURLs[$page['application_id']][$page['id']] = $page['uri'];
            if ($page['no_index'])
                $this->pageURLs['no_index_pages'][$page['uri']] = true;
        }

        foreach($applications as $appId)
        {
            $this->applicationsSitePath[$appId] = parse_url(\App()->SystemSettings->getSettingForApp($appId, 'SiteUrl'), PHP_URL_PATH);
        }

        $this->systemUrlBase = \App()->SystemSettings['SystemUrlBase'];
    }

    public function isPageNoIndex($uri)
    {
        return empty($this->pageURLs['no_index_pages'][$uri]);
    }

    public function getPageURIById($pageId, $applicationId = null)
    {
        $applicationId = is_null($applicationId) ? \App()->SystemSettings['ApplicationID'] : $applicationId;

        if(!isset($this->pageURLs[$applicationId][$pageId]))
        {
            throw new SitePageNotFoundException('Site page with id "' . $pageId . '" not fount');
        }

        return $this->pageURLs[$applicationId][$pageId];
    }

    public function getPageURLById($pageId, $applicationId = null)
    {
        $applicationId = is_null($applicationId) ? \App()->SystemSettings['ApplicationID'] : $applicationId;
        return \App()->SystemSettings->getSettingForApp($applicationId, 'SiteUrl') . $this->getPageURIById($pageId, $applicationId);
    }

    public function getPagePathById($pageId, $applicationId = null)
    {
        $applicationId = is_null($applicationId) ? \App()->SystemSettings['ApplicationID'] : $applicationId;
        return $this->applicationsSitePath[$applicationId] . $this->getPageURIById($pageId, $applicationId);
    }

    public function getSystemPageURI($moduleName, $function)
    {
        return '/'. $this->systemUrlBase .'/' . $moduleName . '/' . $function . '/';
    }

    public function getSystemPageURL($moduleName, $function, $applicationId = null)
    {
        $applicationId = is_null($applicationId) ? \App()->SystemSettings['ApplicationID'] : $applicationId;
        return \App()->SystemSettings->getSettingForApp($applicationId, 'SiteUrl') . $this->getSystemPageURI($moduleName, $function);
    }

    public function getSystemPagePath($moduleName, $function, $applicationId = null)
    {
        $applicationId = is_null($applicationId) ? \App()->SystemSettings['ApplicationID'] : $applicationId;
        return $this->applicationsSitePath[$applicationId] . $this->getSystemPageURI($moduleName, $function);
    }

}
