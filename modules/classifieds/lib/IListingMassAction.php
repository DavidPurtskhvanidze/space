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

/**
 * Mass action for the selected listings
 *
 * Used to define mass action for the selected listings in the admin panel manage listings page
 *
 * @category ExtensionPoint
 */
interface IListingMassAction
{
	/**
	 * Returns URI of the action
	 * @return string
	 */
	public function getUri();

	/**
	 * Returns caption of the action
	 * @return string
	 */
	public function getCaption();
}
