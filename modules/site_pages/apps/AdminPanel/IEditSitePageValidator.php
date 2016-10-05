<?php
/**
 *
 *    Module: site_pages v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: site_pages-7.5.0-1
 *    Tag: tags/7.5.0-1@19834, 2016-06-17 13:21:53
 *
 *    This file is part of the 'site_pages' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\site_pages\apps\AdminPanel;

/**
 * Edit sie page validator
 * 
 * If returns false, site page won't be edited (saved).
 * 
 * @category ExtensionPoint
 */
interface IEditSitePageValidator
{
	/**
	 * Setter of site page old uri
	 * @param string $oldUri
	 */
	public function setOldUri($oldUri);
	/**
	 * Setter of site page info
	 * @param array $pageInfo
	 */
	public function setPageInfo($pageInfo);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
