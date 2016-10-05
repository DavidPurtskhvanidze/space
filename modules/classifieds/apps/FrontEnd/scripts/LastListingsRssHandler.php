<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\apps\FrontEnd\scripts;

class LastListingsRssHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Last Listings  RSS';
	protected $moduleName = 'classifieds';
	protected $functionName = 'last_listings_rss';
	protected $rawOutput = true;

	public function respond()
	{
		header("Content-type:application/xml;charset=utf-8;", true);
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign("listings", $this->getListings($this->getSearch()));
		$template_processor->display("last_listings_rss.tpl");
	}
	private function getSearch()
	{
						$search = new \lib\ORM\SearchEngine\Search();
		$search->setPage(1);
		$search->setObjectsPerPage($this->getNumberOfListings());
		$search->setRequest($this->getRequest());
		$search->setDB(\App()->DB);
		$search->setRowMapper(new \modules\classifieds\lib\ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
		$search->setModelObject($this->getModelListing());
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
        $search->setSortingFields(array('activation_date' => 'DESC'));
		return $search;
	}
	private function getListings($search)
	{
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
	}
	private function getRequest()
	{
		return array('active' => array('equal' => 1));
	}
	private function getModelListing()
	{
		return \App()->ListingFactory->getListing(array(), 0);
	}
	private function getNumberOfListings()
	{
		return \App()->CustomSettings->getSettingValue('number_of_last_listings_for_rss');
	}
}
