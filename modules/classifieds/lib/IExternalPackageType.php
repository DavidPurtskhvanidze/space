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


namespace modules\classifieds\lib;

class IExternalPackageType implements \modules\membership_plan\lib\IExternalPackageType
{
    public function getTypeID()
    {
        return 'ListingPackage';
    }

    public function getData()
    {
        return [
            'id' => $this->getTypeID(),
            'name' => 'Listing Package',
            'class_name' => 'ListingPackage',
            'package_class' => '\modules\classifieds\lib\ListingPackage',
            'package_details_for_display_actions' => 'modules\membership_plan\apps\FrontEnd\IPackageDetailsForDisplay',
            'packages_template' => 'classifieds^membership_plan/packages.tpl',
        ];
    }
}
