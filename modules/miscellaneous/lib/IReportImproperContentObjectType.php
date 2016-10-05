<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

/**
 * Object type for report improper content action
 *
 * Interfaces designed to define an object type for the improper content action
 *
 * @category ExtensionPoint
 */
interface IReportImproperContentObjectType
{
	/**
	 * Returns the type of the object
	 * @return string type of the object
	 */
	public function getType();

	/**
	 * Checks whether object exists or not
	 * @param int $objectSid sid of the object to check
	 * @return boolean true if object exists false otherwise
	 */
	public function doesObjectExist($objectSid);

	/**
	 * Returns the message template name
	 * @return string template name of the message which will be sent to the administrator
	 */
	public function getMessageTemplateName();

	/**
	 * Returns the parameters which will be assigned to the template
	 * @param int $objectSid sid of the object
	 * @return array parameters which will be assigned to the message template
	 */
	public function getMessageParameters($objectSid);
}
