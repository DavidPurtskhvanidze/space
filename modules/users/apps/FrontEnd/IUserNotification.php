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
 * Interface for the user notification
 *
 * Used to store user notification setting in the database
 *
 * @category ExtensionPoint
 */
interface IUserNotification
{
	/**
	 * Getter for Id of the user notification
	 * @return string Id of the user notification
	 */
	public function getId();

	/**
	 * Getter for caption of the user notification
	 * @return string caption of the user notification
	 */
	public function getCaption();

	/**
	 * Getter for the status of the user notification
	 * @param int $userSid user's sid
	 * @return int 0 - if notification is turned off, 1 - if it's turned on
	 */
	public function getValue($userSid);

	/**
	 * Setter for the status of the user notification
	 * @param int $userSid user's sid
	 * @param int $value 0 - to turned off the notification, 1 - to turned on
	 */
	public function setValue($userSid, $value);
}
