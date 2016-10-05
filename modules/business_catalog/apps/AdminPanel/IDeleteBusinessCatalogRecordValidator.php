<?php
/**
 *
 *    Module: business_catalog v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: business_catalog-7.5.0-1
 *    Tag: tags/7.5.0-1@19772, 2016-06-17 13:18:58
 *
 *    This file is part of the 'business_catalog' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\business_catalog\apps\AdminPanel;

/**
 * Delete business catalog record validator
 * 
 * If returns false, business catalog record won't be deleted.
 * 
 * @category ExtensionPoint
 */
interface IDeleteBusinessCatalogRecordValidator
{
	/**
	 * Setter of record id
	 * @param string $id
	 */
	public function setId($id);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
