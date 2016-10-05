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


namespace modules\export_listings\apps\AdminPanel\scripts;

use apps\AdminPanel\ContentHandlerBase;
use modules\classifieds\lib\ListingsFactoryToRowMapperAdapter;
use modules\export_listings\lib\ExportListingsFactory;

class ArchiveAndSendExportDataHandler extends ContentHandlerBase
{
	protected $moduleName = 'export_listings';
	protected $functionName = 'archive_and_send_export_data';
	protected $rawOutput = true;

	private $modelListing;
	
	public function respond()
	{
		$this->modelListing = \App()->ListingFactory->getListing(array(), \App()->Session->getContainer('EXPORT_LISTINGS')->getValue('category_sid'));
		$search = $this->getSearch();
		$requestData = \App()->ObjectMother->createReflectionFactory()->createHashtableReflector($search->getRequest());
		$exportListingsFactory = new ExportListingsFactory();
		$exportListings = $exportListingsFactory->createDataTransceiver($requestData, $search);
		$exportListings->perform();
		$exportListings->finalize();
	}
	
	private function getSearch()
	{
		$search = unserialize(\App()->Session->getContainer('EXPORT_LISTINGS')->getValue('search'));
		$search->setDB(\App()->DB);
		$search->setRowMapper(new ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
		$search->setModelObject($this->modelListing);
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		return $search;
	}
}
