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

/**
 * Interface designed for usage of access control management in Admin Panel.
 * @category ExtensionPoint
 */
interface IAccessControl
{

    /**
     * returns the name of administrator group related to current AccessControl class
     * @return mixed
     */
    public function getGroupName();

    /**
     * Performs some admin account relative actions for further usage
     * @param $username
     * @return mixed
     */
    public function defineUserByUsername($username);

    /**
     * Checks if the logged in admin (defined via defineUserByUsername())
     * has access to function given as argument
     * @param $moduleName
     * @param $functionName
     * @return boolean
     */
    public function hasAccess($moduleName, $functionName);

    /**
     * Action to perform on admin logout
     */
    public function onLogout();

    /**
     * Checks if Administrator account is active
     * @return boolean
     */
    public function isAdministratorActive($username);

    /**
     * Returns Email Of Admin
     * @return String
     */

    public function getEmailByUsername($username);
}
