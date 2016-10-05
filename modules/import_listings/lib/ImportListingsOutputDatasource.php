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

class ImportListingsOutputDatasource implements \lib\DataTransceiver\IOutputDatasource
{
	var $listValuesProcessor;
	var $treeValuesProcessor;
	var $listingManager;
	var $importedListingImageUploader;

	function setListValuesProcessor($listValuesProcessor)
	{
		$this->listValuesProcessor = $listValuesProcessor;
	}
	function setTreeValuesProcessor($treeValuesProcessor)
	{
		$this->treeValuesProcessor = $treeValuesProcessor;
	}
	function setListingManager($listingManager)
	{
		$this->listingManager = $listingManager;
	}
	function setImportedListingImageUploader($importedListingImageUploader)
	{
		$this->importedListingImageUploader = $importedListingImageUploader;
	}
	function processListingImages($listing, $listingData)
	{
		$this->importedListingImageUploader->processImages($listing, $listingData);
	}
	function add($importedListing)
	{
		$listingSid = null;
		$listing = $importedListing->getListing();

		$this->listValuesProcessor->processObject($listing);
		$this->treeValuesProcessor->processObject($listing, $importedListing->getListingData());
		$this->listingManager->saveListing($listing);
		$this->processListingImages($listing, $importedListing->getListingData());
	}
	
	function canAdd($importedListing)
	{
		return true;
	}

	public function setUpdateOnMatch($updateOnMatch)
	{
	}

	public function setUniqueFieldId($uniqueFieldId)
	{
	}

	public function setListingsUniqueValues($listingsUniqueValues)
	{
	}

	public function getUniqueFieldId()
	{
	}

	public function finalize()
	{
	}

	public function setDeleteOnMiss($deleteOnMiss)
	{
	}

	public function setLogger($logger)
	{
	}

	public function setCollectionSaver($collectionSaver)
	{
	}
}
