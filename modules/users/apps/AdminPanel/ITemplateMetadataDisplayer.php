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


namespace modules\users\apps\AdminPanel;

/**
 * Display user's extra data
 *
 * Used to display user's extra data taken from the other modules in the Manage Users page in the Admin Panel
 *
 * @category ExtensionPoint
 */
interface ITemplateMetadataDisplayer
{
	/**
	 * Setter of the user sid
	 * @param int $userSid sid of the user
	 */
	public function setUserSid($userSid);

	/**
	 * Displays user's extra data. Content should be wrapped with <li></li> tag!!!
	 */
	public function display();
}
