<?php
/**
 *
 *    Module: users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: users-7.5.0-1
 *    Tag: tags/7.5.0-1@19887, 2016-06-17 13:25:03
 *
 *    This file is part of the 'users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\users\apps\FrontEnd;

/**
 * Delete user logo validator
 * 
 * Interface designed for validating delete user logo action in FrontEnd. If it returns false, user logo will not be deleted.
 * 
 * @category ExtensionPoint
 */
interface IDeleteUserLogoValidator
{
/**
 * Setter of username
 * @param string $username
 */
public function setUsername($username);
/**
 * Action validator
 * @return boolean
 */
public function isValid();
}
