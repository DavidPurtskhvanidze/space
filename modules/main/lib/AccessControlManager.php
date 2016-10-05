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


namespace modules\main\lib;

class AccessControlManager extends \lib\ORM\ObjectManager implements \core\IService
{
    private $accessControls;
    private $modulesFunctionsConfig;

    public function init()
    {
        $this->accessControls = new \core\ExtensionPoint('modules\main\apps\AdminPanel\IAccessControl');
        $appID = \App()->SystemSettings['ApplicationID'];
        $this->modulesFunctionsConfig = \App()->ModuleManager->getModulesFunctionsConfigForApp($appID);
    }

    public function isGroupActive($groupName)
    {
        foreach ($this->accessControls as $accessControl)
        {
            if ($groupName == $accessControl->getGroupName())
            {
                return true;
            }
        }
        return false;
    }

    public function isAdministratorActive($username)
    {
        $accessControl = $this->getAccessControlByUsername($username);
        return $accessControl->isAdministratorActive($username);
    }

    public function setAccessDataControlByUsername($username)
    {
        $accessControl = $this->getAccessControlByUsername($username);
        \App()->Session->setValue('accessControlClassName', get_class($accessControl));
        $accessControl->defineUserByUsername($username);
        return true;
    }

    public function hasAccess($moduleName, $functionName)
    {
        $isPermissionRequired = $this->modulesFunctionsConfig->isPermissionRequired($moduleName, $functionName);
        if ($isPermissionRequired)
        {
            $accessControl = $this->getAccessControl();
            return $accessControl->hasAccess($moduleName, $functionName);
        }
        else
        {
            return true;
        }
    }

    public function onLogout(){
        $accessControl = $this->getAccessControl();
        $accessControl->onLogout();
        \App()->Session->unsetValue('accessControlClassName');
    }

    /**
     * @return \modules\main\apps\AdminPanel\IAccessControl
     */
    public function getAccessControl()
    {
        $accessControlClassName = \App()->Session->getValue('accessControlClassName');
        return new $accessControlClassName;
    }

    private function getAccessControlByUsername($username)
    {
        $adminManager = new \modules\main\lib\AdminManager();
        $groupName = $adminManager->getAdminGroupByUsername($username);
        foreach ($this->accessControls as $accessControl)
        {
            if ($groupName == $accessControl->getGroupName())
            {
                return $accessControl;
            }
        }
    }

    public function getAdminEmailByUsername($username)
    {
        /** @var $accessControl \modules\main\apps\AdminPanel\IAccessControl */
        $accessControl = $this->getAccessControlByUsername($username);
        return $accessControl->getEmailByUsername($username);
    }
}
