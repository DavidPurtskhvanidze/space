<?php
/**
 *
 *    Module: listing_feature_featured v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_featured-7.5.0-1
 *    Tag: tags/7.5.0-1@19791, 2016-06-17 13:19:46
 *
 *    This file is part of the 'listing_feature_featured' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_featured\apps\FrontEnd\scripts;

class FeaturedListingsRssHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Featured Listings RSS';
	protected $moduleName = 'listing_feature_featured';
	protected $functionName = 'featured_listings_rss';
	protected $rawOutput = true;

	public function respond()
	{
		header("Content-type:application/xml;charset=utf-8;", true);
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign("listings", $this->getListings($this->getSearch()));
		$template_processor->display("featured_listings_rss.tpl");
	}
	private function getSearch()
	{
		$search = new \lib\ORM\SearchEngine\Search();
		$search->setPage(1);
		$search->setObjectsPerPage(1000);
		$search->setRequest($this->getRequest());
		$search->setDB(\App()->DB);
		$search->setRowMapper(new \modules\classifieds\lib\ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
		$search->setModelObject($this->getModelListing());
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
        $search->setSortingFields(array('activation_date'=>'DESC'));
		return $search;
	}
	private function getListings($search)
	{
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
	}
	private function getRequest()
	{
		return array('active' => array('equal' => 1), 'feature_featured' => array('equal' => 1));
	}
	private function getModelListing()
	{
		return \App()->ListingFactory->getListing(array(), 0);
	}
}
