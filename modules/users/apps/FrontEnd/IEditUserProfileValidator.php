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
 * Edit user profile validator
 *
 * Interface designed for validating edit user profile action in FrontEnd. If it returns false, user profile will not be saved.
 *
 * @category ExtensionPoint
 */
interface IEditUserProfileValidator
{
	/**
	 * Setter of user object
	 * @param \modules\users\lib\User\User $user
	 */
	public function setUser($user);

	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
