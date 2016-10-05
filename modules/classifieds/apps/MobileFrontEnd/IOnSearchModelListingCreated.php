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


namespace modules\classifieds\apps\MobileFrontEnd;

/**
 * On search model listing created action interface.
 * 
 * Interface designed for performing action on search model listing created.
 * 
 * @category ExtensionPiont
 */
interface IOnSearchModelListingCreated
{
	/**
	 * Model listing setter
	 * @param modules\classifieds\lib\Listing\Listing $modelListing
	 */
	public function setModelListing($modelListing);
	/**
	 * Action executer
	 */
	public function perform();
}
