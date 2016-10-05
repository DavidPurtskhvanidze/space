<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\ORM\SearchEngine;
class SearchArrayAdapter implements \ArrayAccess
{
    /**
     * @var Search
     */
    private $search;
	public function __construct($search)
	{
		$this->search = $search;
	}

	public function offsetGet($index)
	{
		switch($index)
		{
			case 'total_found' : return $this->search->getNumberOfObjectsFound();
			case 'current_page' : return $this->search->getPage();
			case 'pages_number' : return $this->search->getNumberOfPages();
			case 'objects_per_page' : return $this->search->getObjectsPerPage();
			case 'sorting_fields' : return $this->search->getSortingFields();
			case 'id' : return $this->search->getId();
			case 'search_form_uri' : return $this->search->getSearchFormUri();
			case 'search_results_uri' : return $this->search->getSearchResultsUri();
			case 'view_type' : return $this->search->getResultViewType();
			case 'next' : return $this->search->getNeighborSids()['next'];
			case 'prev' : return $this->search->getNeighborSids()['prev'];
			default: throw new \Exception("Unknown key \"$index\" requested");
		}
	}	
	
	public function offsetExists($index){return false;}
	public function offsetSet($offset, $value){throw new \Exception("Search is a read-only object");}
	public function offsetUnset($offset){throw new \Exception("Search is a read-only object");}
	public function isSortable($fieldId)
	{
		return $this->search->isSortable($fieldId);
	}

}
