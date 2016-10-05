<?php
/**
 *
 *    Module: export_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19779, 2016-06-17 13:19:16
 *
 *    This file is part of the 'export_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_listings\lib;

class ExportListingsDataConverter implements \lib\DataTransceiver\IDataConverter
{
	var $fieldsScheme;
	var $userManager;
	var $categoryManager;
	var $geoFieldsIds;

	public function setCategoryManager($categoryManager)
	{
		$this->categoryManager = $categoryManager;
	}

	public function setGeoFieldsIds($geoFieldsIds)
	{
		$this->geoFieldsIds = $geoFieldsIds;
	}

	public function setUserManager($userManager)
	{
		$this->userManager = $userManager;
	}

	function setFieldsScheme($fieldsScheme)
	{
		$this->fieldsScheme = $fieldsScheme;
	}

	/**
	 * @param \modules\classifieds\lib\Listing\Listing $listing
	 * @return mixed|ExportedListing
	 */
	function getConverted($listing)
	{
		$listing->addUsernameProperty($this->userManager->getUserNameByUserSID($listing->getUserSID()));
		$listing->addProperty
		(
			array
			(
				'id'		=> 'category',
				'type'		=> 'string',
				'value'		=> $this->categoryManager->getCategoryIDBySID($listing->getCategorySID()),
			)
		);
		$listingInfo = array();
		foreach ($this->fieldsScheme as $propertyId)
		{
			$listingInfo[$propertyId] = $listing->getPropertyExportValue($propertyId);
			if (in_array($propertyId, $this->geoFieldsIds)) $listingInfo[$propertyId] = (string) $listingInfo[$propertyId];
		}
		if (!isset($listingInfo['id']))
		{
			$listingInfo['id'] = $listing->getPropertyExportValue('id');
		}
		$converted = new ExportedListing();
		$converted->setData($listingInfo);
		$converted->setListing($listing);
		return $converted;
	}
}

class ExportedListing
{
	private $listing;
	private $data;
	
	public function setListing($listing)
	{
		$this->listing = $listing;
	}
	public function setData($data)
	{
		$this->data = $data;
	}
	public function getListingSid()
	{
		return $this->listing->getSID();
	}
	public function getData()
	{
		return $this->data;
	}
}
