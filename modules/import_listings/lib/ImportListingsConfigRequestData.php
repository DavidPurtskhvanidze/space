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

class ImportListingsConfigRequestData implements IImportListingsConfig
{
	private $filePath;

	public function __construct()
	{
		$this->extraData = array
		(
			'csvFileDelimiter' => \App()->Request['csv_delimiter']
		);
	}

	public function getDefaultCategorySid()
	{
		return \App()->Request['category_sid'];
	}
	public function getDefaultUserSid()
	{
		return null;
	}
	public function getListingPackageSid()
	{
		return \App()->Request['listing_package'];
	}
	public function getActivateListing()
	{
		return \App()->Request['active'];
	}
	public function getActivationDate()
	{
		return \App()->Request['activation_date'];
	}
	public function getAddOptions()
	{
		return false;
	}
	public function getAddListValues()
	{
		return \App()->Request['non_existed_values'] == 'add';
	}
	public function getAddTreeValues()
	{
		return \App()->Request['non_existed_values'] == 'add';
	}
	public function getFilePath()
	{
		return $this->filePath;
	}
	public function getUniqueFieldSid()
	{
		return null;
	}
	public function getUpdateOnMatch()
	{
		return false;
	}

	public function getDeleteOnMiss()
	{
		return false;
	}

	public function getImportFormat()
	{
		return \App()->Request['file_type'];
	}

	public function getExtraDataValue($name)
	{
		return isset($this->extraData[$name]) ? $this->extraData[$name] : null;
	}

	public function getDataConverterClassName()
	{
		return '\modules\import_listings\lib\ImportListingsDataConverter';
	}
	public function getOutputDataSourceClassName()
	{
		return '\modules\import_listings\lib\ImportListingsOutputDatasource';
	}
	public function getListingPictureStorageMethod()
	{
		return null;
	}
	public function getListingNode()
	{
		return null;
	}
	public function getGroupedQuerySize()
	{
		return 1;
	}
	public function getLocalFileName()
	{
		return 'localImportListingsSource';
	}

	public function setFilePath($filePath)
	{
		$this->filePath = $filePath;
	}

	public function getIncludedFeaturesList()
	{
		return !is_null(\App()->Request['included_features']) ? \App()->Request['included_features'] : array();
	}
}
