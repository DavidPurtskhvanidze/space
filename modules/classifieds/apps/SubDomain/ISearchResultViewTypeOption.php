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
 * Search result view type option provider
 * 
 * Interface designed for providing options for search result view types.
 * 
 * @category ExtensionPoint
 */
interface ISearchResultViewTypeOption
{
	/**
	 * Setter of Search object
	 * @param \lib\ORM\SearchEngine\Search $search
	 */
	public function setSearch(\lib\ORM\SearchEngine\Search $search);
	/**
	 * Getter of search result view type option id
	 * @return string
	 */
	public static function getOptionId();
	/**
	 * Getter of template for rendering search results
	 * @return string
	 */
	public function getSearchResultTemplateName();
	/**
	 * Returns rendered option
	 * @return string
	 */
	public function getRenderedOption();
	/**
 	* Returns control order.
	 * @return integer
	 */
	public static function getOrder();
}
