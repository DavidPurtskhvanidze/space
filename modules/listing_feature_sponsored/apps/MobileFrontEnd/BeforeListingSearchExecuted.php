<?php
/**
 *
 *    Module: listing_feature_sponsored v.7.4.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_sponsored-7.4.0-1
 *    Tag: tags/7.4.0-1@19153, 2016-01-11 11:20:12
 *
 *    This file is part of the 'listing_feature_sponsored' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_sponsored\apps\MobileFrontEnd;

class BeforeListingSearchExecuted implements \modules\classifieds\apps\MobileFrontEnd\IBeforeListingSearchExecuted
{
	/**
	 * Listing search object
	 * @var \lib\ORM\SearchEngine\Search
	 */
	private $search;
	public function setSearch($search)
	{
		$this->search = $search;
	}
	public function perform()
	{
		$defaultSortingFields = array();
		if (\App()->Request->getValueOrDefault('default_sorting_field'))
		{
			$defaultSortingFields = array(\App()->Request->getValueOrDefault('default_sorting_field') => \App()->Request->getValueOrDefault('default_sorting_order', 'ASC'));
		}
		
		$sortingFields = $this->search->getSortingFields();
		$sortingFieldsDiff = array_diff_assoc($defaultSortingFields, $sortingFields);
		if (empty($sortingFieldsDiff))
		{
			$this->search->setSortingFields(array('feature_sponsored'=>'DESC') + $sortingFields);
		}
	}
}
