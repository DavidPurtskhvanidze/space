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


namespace modules\classifieds\lib\Listing;

class ListingDisplayer 
{
	/* deps */
	
	private $template_processor;
	public function setTemplateProcessor($tp){$this->template_processor = $tp;}
	
	private $CategoryManager;
	public function setCategoryManager($m){$this->CategoryManager = $m;}

	private $objectToArrayAdapterFactory;
	public function setObjectToArrayAdapterFactory($objectToArrayAdapterFactory)
	{
		$this->objectToArrayAdapterFactory = $objectToArrayAdapterFactory;
	}
	
	private $listingsIdsInComparison = array();
	public function setListingsIdsInComparison($listingsIdsInComparison)
	{
		$this->listingsIdsInComparison = $listingsIdsInComparison;
	}
	
	private $savedListingsIds = array();
	public function setSavedListingsIds($savedListingsIds)
	{
		$this->savedListingsIds = $savedListingsIds;
	}
	
	function displayListing($params)
	{
		$listing = $params['listing'];
		unset($params['listing']);
		$listingTemplate = isset($params['listingTemplate']) ? $params['listingTemplate'] :
			$this->CategoryManager->getCategorySearchResultTemplateFileName($listing->getCategorySID());
		unset($params['listingTemplate']);
		$this->template_processor->assign("listing", $this->wrapListing($listing));
		$this->template_processor->assign($params);
		return $this->template_processor->fetch($listingTemplate);
	}
	
	function wrapListing($listing)
	{
		$listing->addProperty(array('id' => 'saved', 'type' => 'boolean', 'value' => in_array($listing->getSid(), $this->savedListingsIds)));
		$listing->addProperty(array('id' => 'inComparison', 'type' => 'boolean', 'value' => in_array($listing->getSid(), $this->listingsIdsInComparison)));
		$adapter = $this->objectToArrayAdapterFactory->getObjectToArrayAdapter($listing);
		return $adapter;
	}
	
	function registerResources($tp)
	{
		$tp->registerPlugin("function", "display_listing", array($this, "displayListing"));
	}

	function getListingCollectionStructure($sorted_found_listings_sids_for_current_page)
	{
		$listings_structure = array();
		$listings_info = \App()->ListingManager->getListingsInfoBySidCollection($sorted_found_listings_sids_for_current_page);

		foreach ($sorted_found_listings_sids_for_current_page as $sid)
		{
			$listing_info = $listings_info[$sid];
			$listing = \App()->ObjectMother->getListingFactory()->getListing($listing_info, $listing_info['category_sid']);
			$listings_structure[$listing->getSid()] = $listing;
		}

		return $listings_structure;
	}
	
}
