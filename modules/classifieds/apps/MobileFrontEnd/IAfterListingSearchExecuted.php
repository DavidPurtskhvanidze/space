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
 * After listing search objects getFoundObjectCollection() function executed action interface.
 * 
 * Interface designed for performing action just before \lib\ORM\SearchEngine\SearchgetFoundObjectCollection()
 * executed for listing search.
 * 
 * @category ExtensionPiont
 */
interface IAfterListingSearchExecuted
{
	/**
	 * Search result object setter
	 * @param \lib\ORM\SearchEngine\ResourceToIterableCollectionAdapter $search
	 */
	public function setSearchResultCollection($searchResultCollection);
	/**
	 * Action executer
	 */
	public function perform();
}
