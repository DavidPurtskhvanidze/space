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
use lib\Http\RedirectException;
use lib\ORM\SearchEngine\Search;
use lib\ORM\SearchEngine\SearchFormBuilder;
use modules\classifieds\apps\AdminPanel\IMenuItem;
use modules\classifieds\lib\ListingsFactoryToRowMapperAdapter;
use modules\export_listings\lib\ExportListingsFactory;

class ExportListingsHandler extends ContentHandlerBase implements IMenuItem
{
	protected $moduleName = 'export_listings';
	protected $functionName = 'export_listings';

	private $modelListing;
	
	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$category_sid = \App()->Request->getValueOrDefault("['category_sid']['tree']['1']", 0);
		$requestData = \App()->ObjectMother->createRequestReflector();
		$this->modelListing = \App()->ListingFactory->getListing([], $category_sid);

		$search_form_builder = new SearchFormBuilder($this->modelListing);
		$search_form_builder->setRequestData($requestData);
		$search_form_builder->registerTags($templateProcessor);

		if (\App()->Request['action'] == 'export')
		{
			$search = $this->getSearch();
			$exportListingsFactory = new ExportListingsFactory();
			$exportListings = $exportListingsFactory->createDataTransceiver($requestData, $search);
			if ($exportListings->canPerform())
			{
				\App()->Session->getContainer('EXPORT_LISTINGS')->setValue('category_sid', $category_sid);
				\App()->Session->getContainer('EXPORT_LISTINGS')->setValue('search', serialize($search));
				throw new RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'archive_and_send_export_data'));
			}
		}
		$templateProcessor->assign("properties", $this->excludeNotExportableFields(\App()->ListingManager->getAllListingProperties($category_sid)));
		$templateProcessor->display("export_listings.tpl");
	}

	private function excludeNotExportableFields($fields)
	{
		return array_filter($fields, [$this, 'isFieldExportable']);
	}
	private function isFieldExportable($field)
	{
		$fieldType = isset($field['type']) ? $field['type'] : null;
		return !in_array($fieldType, ['rating', 'calendar']);
	}
	private function getSearch()
	{
		$search = new Search();
		$search->setRequest($_REQUEST);
		$search->setPage(1);
		$search->setObjectsPerPage(1000000);
		$search->setDB(\App()->DB);
		$search->setRowMapper(new ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
		$search->setModelObject($this->modelListing);
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		return $search;
	}

	public static function getOrder()
	{
		return 600;
	}

	public function getCaption()
	{
		return "Export Listings";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getSystemPageURL($this->moduleName, $this->functionName);
	}

	public function getHighlightUrls()
	{
		return [];
	}
}
