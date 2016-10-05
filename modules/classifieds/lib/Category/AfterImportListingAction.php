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

namespace modules\classifieds\lib\Category;

class AfterImportListingAction implements \modules\import_listings\apps\IAfterImportListingsAction
{
    /**
     * @var \modules\import_listings\lib\IImportListingsConfig
     */
    private $config;

    /**
     * Action executer
     */
    public function perform()
    {
        $categorySid = $this->config->getDefaultCategorySid();
        $allListingsCount = \App()->DB->getSingleValue("SELECT count(*) FROM `classifieds_listings` WHERE `category_sid` = ?n", $categorySid);
        $activeListingsCount = \App()->DB->getSingleValue("SELECT count(*) FROM `classifieds_listings` WHERE `category_sid` = ?n AND `active` = 1", $categorySid);
        \App()->DB->query("UPDATE `classifieds_categories` SET `active_listing_number` = ?n, `listing_number` = ?n WHERE `sid` = ?n", $activeListingsCount, $allListingsCount, $categorySid);
    }

    public function setImportConfig($config)
    {
        $this->config = $config;
    }
}
