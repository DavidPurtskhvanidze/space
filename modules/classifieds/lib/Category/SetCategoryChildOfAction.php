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


namespace modules\classifieds\lib\Category;

class SetCategoryChildOfAction
{
	var $errors = array();
	
	function setCategoryManager(&$categoryManager)
	{
		$this->categoryManager = $categoryManager;
	}
	
	function setListingManager(&$listingManager)
	{
		$this->listingManager = $listingManager;
	}
	
	function setTreeWalker(&$treeWalker)
	{
		$this->treeWalker = $treeWalker;
	}
	
	function setListingCountSummator(&$listingCountSummator)
	{
		$this->listingCountSummator = $listingCountSummator;
	}
	
	function setListingFieldManager(&$listingFieldManager)
	{
		$this->listingFieldManager = $listingFieldManager;
	}
	
	function setListingFieldCollector(&$listingFieldCollector)
	{
		$this->listingFieldCollector = $listingFieldCollector;
	}
	
	function setCategorySID($category_sid)
	{
		$this->category_sid = $category_sid;
	}
	
	function setDestinationCategorySID($dest_category_sid)
	{
		$this->dest_category_sid = $dest_category_sid;
	}
	
	function canPerform()
	{
		if (!$this->categoryManager->doesCategoryExist($this->category_sid))
		{
			$this->errors['NON_EXISTED_CATEGORY'] = 'NON_EXISTED_CATEGORY';
			\App()->ErrorMessages->addMessage('NON_EXISTED_CATEGORY');
		}
		elseif ($this->doesCategoryHaveListings($this->category_sid))
		{
			$this->errors['CATEGORY_HAS_LISTINGS'] = 'CATEGORY_HAS_LISTINGS';
			\App()->ErrorMessages->addMessage('CATEGORY_HAS_LISTINGS', array('relocatingCategoryId' => $this->categoryManager->getCategoryIDBySID($this->category_sid)));
		}
		if (!$this->categoryManager->doesCategoryExist($this->dest_category_sid))
		{
			$this->errors['NON_EXISTED_DESTINATION_CATEGORY'] = 'NON_EXISTED_DESTINATION_CATEGORY';
			\App()->ErrorMessages->addMessage('NON_EXISTED_DESTINATION_CATEGORY');
		}
		if ($this->doCategoriesHaveSameFields($this->category_sid, $this->dest_category_sid))
		{
			$this->errors['CATEGORIES_HAVE_SAME_FIELDS'] = 'CATEGORIES_HAVE_SAME_FIELDS';
			$relocatingCategoryId = $this->categoryManager->getCategoryIDBySID($this->category_sid);
			$destinationCategoryId = $this->categoryManager->getCategoryIDBySID($this->dest_category_sid);
			\App()->ErrorMessages->addMessage('CATEGORIES_HAVE_SAME_FIELDS', array('relocatingCategoryId' => $relocatingCategoryId, 'destinationCategoryId' => $destinationCategoryId));
		}
		return empty($this->errors);
	}
	
	function doesCategoryHaveListings($category_sid)
	{
		$listing_counts = $this->listingManager->getListingCounts();
		
		$categoryTreeData = $this->categoryManager->getTree();
		$categoryTreeData->addScalarExtraParameters($listing_counts, 'category_sid', 'listing_count', 'listing_count');
		
		$category = $categoryTreeData->getItem($category_sid);
		
		$this->treeWalker->setHandler($this->listingCountSummator);
		$this->treeWalker->walkDown($category);
		$listing_count = $this->listingCountSummator->getSum();
		
		return $listing_count > 0;
	}
	
	function doCategoriesHaveSameFields($category_sid, $dest_category_sid)
	{
		$listing_fields = $this->listingFieldManager->getListingFields();
		
		$categoryTreeData = $this->categoryManager->getTree();
		$categoryTreeData->addArrayExtraParameters($listing_fields, 'category_sid', 'id', 'listing_field_id');
		
		$this->treeWalker->setHandler($this->listingFieldCollector);
		
		$category = $categoryTreeData->getItem($category_sid);
		$this->treeWalker->walkDown($category);
		$subtreeListingFieldCollection = $this->listingFieldCollector->getCollection();
		
		$this->listingFieldCollector->reset();
		
		$destCategory = $categoryTreeData->getItem($dest_category_sid);
		$this->treeWalker->walkUp($destCategory);
		$ancestorsListingFieldCollection = $this->listingFieldCollector->getCollection();

		$wrappedFunctions = new \core\WrappedFunctions();
		$same_fields = $wrappedFunctions->array_intersect($subtreeListingFieldCollection, $ancestorsListingFieldCollection);
		
		return !empty($same_fields);
	}
	
	function perform()
	{
		$this->categoryManager->setChildOf($this->category_sid, $this->dest_category_sid);
	}
	
	function getErrors()
	{
		return $this->errors;
	}
}

?>
