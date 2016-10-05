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


namespace modules\classifieds\apps\SubDomain;

/**
 * 'Display Listing' page control template provider
 *
 * Used to define a listing control on the listing details page of the front end.
 *
 * @category ExtensionPoint
 */
interface IDisplayListingPageControlTemplateProvider
{
	/**
	 * Returns template filename which will be included in the listing contols block
	 * @return string
	 */
	public function getTemplateName();
}
