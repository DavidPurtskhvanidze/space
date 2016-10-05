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


namespace modules\users\lib\User;

/**
 * Action to perform on a new user added
 *
 * Interface designed for actions to perform on new user's added
 *
 * @category ExtensionPoint
 */
interface IOnUserAddedAction
{
	/**
	 * Setter for user object
	 * @param \modules\users\lib\User\User $user added user
	 */
	public function setUser($user);

	/**
	 * Action performer
	 */
	public function perform();

}
