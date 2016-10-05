<?php
/**
 *
 *    Module: import_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19787, 2016-06-17 13:19:36
 *
 *    This file is part of the 'import_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_listings\lib;

/**
 * Import configuration set
 * 
 * Interface designed for implementing import configuration sets. Configuration set keeps information about input data 
 * source, options default data and any other configuration data.
 */
interface IImportListingsConfig extends \lib\DataTransceiver\Import\IImportConfig
{
	/**
	 * Returns default category Sid of current config object
	 * @return int
	 */
	public function getDefaultCategorySid();
	/**
	 * Returns default user Sid of current config object
	 * @return int
	 */
	public function getDefaultUserSid();
	/**
	 * Returns default listing package Sid of current config object
	 * @return int
	 */
	public function getListingPackageSid();
	/**
	 * Returns array of free features of current config object that should be added by default
	 * @return array
	 */
	public function getIncludedFeaturesList();
	/**
	 * Returns default status of current config object for listing activated status
	 * @return bool
	 */
	public function getActivateListing();
	/**
	 * Returns default activation date of current config object
	 * @return bool
	 */
	public function getActivationDate(); //TODO: ask Lena Kosyakova if we need this option
	/**
	 * Returns boolean flag to add boolean options or not
	 * @return bool
	 */
	public function getAddOptions();
	/**
	 * Returns boolean flag to add list values or not
	 * @return bool
	 */
	public function getAddListValues();
	/**
	 * Returns boolean flag to add tree values or not
	 * @return bool
	 */
	public function getAddTreeValues();
	/**
	 * Returns unique field sid for record match checking.
	 * @return int
	 */
	public function getUniqueFieldSid();
	/**
	 * Returns boolean flag to update matching(by unique field id) record or not
	 * @return bool
	 */
	public function getUpdateOnMatch();
	/**
	 * Returns boolean flag to delete missing(by unique field id) record or not
	 * @return bool
	 */
	public function getDeleteOnMiss();
	/**
	 * Returns data converter class name including namespace
	 * @return string
	 */
	public function getDataConverterClassName();
	/**
	 * Returns output data source class name including namespace
	 * @return string
	 */
	public function getOutputDataSourceClassName();
	/**
	 * Returns listing picture storage method. If null then system values is used
	 * @return bool
	 */
	public function getListingPictureStorageMethod();
	/**
	 * Return Listing node name(only if input data in XML format)
	 * @return string
	 */
	public function getListingNode();
	/**
	 * Return query size
	 * @return int
	 */
	public function getGroupedQuerySize();
}
