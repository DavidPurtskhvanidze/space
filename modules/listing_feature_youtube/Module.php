<?php
/**
 *
 *    Module: listing_feature_youtube v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_youtube-7.5.0-1
 *    Tag: tags/7.5.0-1@19793, 2016-06-17 13:19:51
 *
 *    This file is part of the 'listing_feature_youtube' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_youtube;

use apps\IModuleTemplateProvider;

class Module extends \core\Module implements IModuleTemplateProvider
{
    protected $name = 'listing_feature_youtube';
    protected $caption = 'YouTube Video';
    protected $version = '7.5.0-1';
    protected $dependencies =
        [
            'classifieds',
        ];

    public function getModuleTemplateProviderName()
    {
        return "YouTube Video";
    }

    public function getModuleTemplateProviderDescription()
    {
        return "YouTube Video templates";
    }

    public function getModuleName()
    {
        return "listing_feature_youtube";
    }

    public function getId()
    {
        return __CLASS__;
    }

    public function getAppIds()
    {
        return ['FrontEnd', 'MobileFrontEnd', 'SubDomain'];
    }
}
