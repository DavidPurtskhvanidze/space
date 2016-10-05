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


namespace modules\classifieds\lib\SavedSearch;

/**
 * Saved searches management interface.
 * 
 * Interface designed for implementing saved searches management operations
 */
interface ISavedSearchStorage
{
	/**
	 * Returns all searches of currnet user
	 * @return array Array of search data
	 */
	function getSearches();
	/**
	 * Performs saves operation of the given search
	 * @param string $name Name of the search
	 * @param array $search Search data
	 * @param array $errors Referense to array to set errors
	 * @return bool True on successful end, False on fail
	 */
	function saveSearch($name, $search, &$errors);
	/**
	 * Performs delete operation of the search by given $id
	 * @param int $id Id of the search
	 */
	function deleteSearch($id);
	/**
	 * Saves enable auto notification status for search whith given $id
	 * @param int $id
	 */
	function enbaleAutonotification($id);
	/**
	 * Saves enable auto notification status for search whith given $id
	 * @param int $id
	 */
	function disableAutonotification($id);
	/**
	 * Returns count of current user's searches
	 * @return int
	 */
	function getSearchCount();
}
