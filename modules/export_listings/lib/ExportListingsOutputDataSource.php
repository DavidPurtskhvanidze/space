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

class ExportListingsOutputDatasource implements \lib\DataTransceiver\IOutputDatasource
{
	var $exportedListingPicturesHandler;
	var $exportedListingVideosHandler;
	var $exportedListingFilesHandler;
	var $exportedListingTreeFieldsHandler;
	var $exportXlsFileHandler;
	var $archiver;
	var $exportListingsEnvironment;

	public function setExportListingsEnvironment($exportListingsEnvironment)
	{
		$this->exportListingsEnvironment = $exportListingsEnvironment;
	}

	public function setExportXlsFileHandler($exportXlsFileHandler)
	{
		$this->exportXlsFileHandler = $exportXlsFileHandler;
	}
	public function setExportedListingVideosHandler($exportedListingVideosHandler)
	{
		$this->exportedListingVideosHandler = $exportedListingVideosHandler;
	}
	public function setExportedListingFilesHandler($exportedListingFilesHandler)
	{
		$this->exportedListingFilesHandler = $exportedListingFilesHandler;
	}
	public function setExportedListingPicturesHandler($exportedListingPicturesHandler)
	{
		$this->exportedListingPicturesHandler = $exportedListingPicturesHandler;
	}
	public function setExportedListingTreeFieldsHandler($exportedListingTreeFieldsHandler)
	{
		$this->exportedListingTreeFieldsHandler = $exportedListingTreeFieldsHandler;
	}
	public function setArchiver($archiver)
	{
		$this->archiver = $archiver;
	}
	function add($exportedListing)
	{
		$this->exportedListingPicturesHandler->handle($exportedListing);
		$this->exportedListingVideosHandler->handle($exportedListing);
		$this->exportedListingFilesHandler->handle($exportedListing);
		$this->exportedListingTreeFieldsHandler->handle($exportedListing);
		$this->exportXlsFileHandler->handle($exportedListing);
	}
	function canAdd($data)
	{
		return true;
	}
	function finalize()
	{
		$this->exportXlsFileHandler->finalize();
		$this->archiver->send();
		$this->exportListingsEnvironment->clear();
	}
}
