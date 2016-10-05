<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\main\apps\AdminPanel;
class AccessControl implements \modules\main\apps\AdminPanel\IAccessControl
{
    public function getGroupName()
    {
        return 'admin';
    }

    public function defineUserByUsername($username)
    {
    }

    public function hasAccess($moduleName, $functionName)
    {
        return true;
    }

    public function onLogout()
    {
    }

    public function isAdministratorActive($username)
    {
        return true;
    }

    public function getEmailByUsername($username)
    {
        return \App()->SettingsFromDB->getSettingByName('notification_email');
    }
}
