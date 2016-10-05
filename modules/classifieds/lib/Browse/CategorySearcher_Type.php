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


namespace modules\classifieds\lib\Browse;

class CategorySearcher_Type extends AbstractCategorySearcher
{
    /**
     * @var \modules\classifieds\lib\Listing\ListingCountSummator
     */
    private $listingCountAdder;

    /**
     * @var \modules\miscellaneous\lib\TreeWalker
     */
    private $treeWalker;

    function __construct($field)
	{
		parent::__construct($field);
	}
	public function init()
	{
		$this->treeWalker = \App()->ObjectMother->createTreeWalker();
		$this->listingCountAdder = \App()->ObjectMother->createListingCountSummator();
	}
	
	function getCategorySid($request_data = null)
	{
		return isset($request_data['category_sid']['tree']) ? end($request_data['category_sid']['tree']) : 0;
	}
	
	function &_decorateItems(&$items, $request_data)
	{
		$categorySid = $this->getCategorySid($request_data);
		$categoryTree = \App()->CategoryManager->getTree();
		$this->setCategoryBranchListingsCount($categoryTree, $items);
		$this->setCategoryCaptions($categoryTree);
		$this->setCategoryIds($categoryTree);

		$category = $categoryTree->getItem($categorySid);
		$this->setCategoryLevels($category);
		$this->setCategoryUrlsForBrowsing($category);
		
		$decoratedItems = $this->createStructureForBrowsing($category);
		return $decoratedItems;
	}

    /**
     * @param \modules\miscellaneous\lib\TreeItem $category
     */
    function setCategoryUrlsForBrowsing(&$category)
	{
		$urlDefiner = new CategoryUrlDefiner();
		$urlDefiner->setRootCategoryId($category->getID());
		$this->treeWalker->setHandler($urlDefiner);
		$this->treeWalker->walkChildren($category);
	}
	
	function createStructureForBrowsing(&$category)
	{
		$structureCreator = new CategoryStructureCreatorForBrowsing();
		$this->treeWalker->setHandler($structureCreator);
		$this->treeWalker->walkChildren($category);
		$structure = $structureCreator->getStructure();
		return $structure;
	}
	
	function setCategoryLevels(&$category)
	{
		$treeLevelCalculator = new TreeLevelCalculator();
		$this->treeWalker->setHandler($treeLevelCalculator);
		$this->treeWalker->walkChildren($category);
	}
	

    /**
     * @param \modules\miscellaneous\lib\TreeData $categoryTree
     * @param $categoryListingCounts
     */
    function setCategoryBranchListingsCount(&$categoryTree, &$categoryListingCounts)
	{
		$categoryTree->addScalarExtraParameters($categoryListingCounts, 'caption', 'count', 'listing_count');
        $categoryTree->unsetExtraParameter('branch_listings_count');
		$categories = $categoryTree->getItems();

		$branchListingsCounter = new BranchListingsCounter();
		$this->treeWalker->setHandler($branchListingsCounter);
		foreach (array_keys($categories) as $i)
		{
			$category = $categories[$i];
			$branchListingsCounter->setListingCount($category->getExtraParameter('listing_count'));
			$this->treeWalker->walkUp($category);
		}
	}
	
	private static $categoryCaptionsWereSet = false;

    /**
     * @param \modules\miscellaneous\lib\TreeData $categoryTree
     */
    function setCategoryCaptions(&$categoryTree)
	{
		if (self::$categoryCaptionsWereSet) return;
		$categoryNames = \App()->DB->query("SELECT `sid`, `name` FROM `classifieds_categories`");
		$categoryTree->addScalarExtraParameters($categoryNames, 'sid', 'name', 'caption');
		self::$categoryCaptionsWereSet = true;
	}

    /**
     * @param \modules\miscellaneous\lib\TreeData $categoryTree
     */
    function setCategoryIds(&$categoryTree)
	{
		$categoryNames = \App()->DB->query("SELECT `sid`, `id` FROM `classifieds_categories`");
		$categoryTree->addScalarExtraParameters($categoryNames, 'sid', 'id', 'category_id');
	}
}

class CategoryStructureCreatorForBrowsing
{
	var $structure = array();

    /**
     * @param \modules\miscellaneous\lib\TreeItem $data
     */
    function handle($data)
	{
		$this->structure[] = array
		(
			'category_id' => $data->getExtraParameter('category_id'),
			'caption' => $data->getExtraParameter('caption'),
			'count' => array_sum($data->getExtraParameter('branch_listings_count')),
			'treeLevel' => $data->getExtraParameter('treeLevel'),
			'url' => $data->getExtraParameter('browseUrl'),
			'propertyDomain' => 'Categories',
		);
	}
	
	function &getStructure()
	{
		return $this->structure;
	}
}

class CategoryUrlDefiner
{
	var $rootCategoryId;

    /**
     * @param \modules\miscellaneous\lib\TreeItem $data
     */
	function handle($data)
	{
		$url = $this->defineUrl($data);
		$data->addScalarExtraParameter('browseUrl', $url);
	}

    /**
     * @param \modules\miscellaneous\lib\TreeItem $data
     * @return String
     */
	function defineUrl($data)
	{
		if ($data->getID() == $this->getRootCategoryId())
		{
			return null;
		}
		
		$parentUrl = null;
		$parent = $data->getParent();
		if (!is_null($parent))
		{
			$parentUrl = $parent->getExtraParameter('browseUrl');
		}
		$caption = str_replace('/', '%252F', $data->getExtraParameter('caption'));
		$url = is_null($parentUrl) ? $caption : $parentUrl . "/" . $caption;
		$url = str_replace(" ", "+", $url);
		return $url;
	}
	
	function setRootCategoryId($categoryId)
	{
		$this->rootCategoryId = $categoryId;
	}
	
	function getRootCategoryId()
	{
		return $this->rootCategoryId;
	}
}

class TreeLevelCalculator
{

    /**
     * @param \modules\miscellaneous\lib\TreeItem $data
     */
	function handle($data)
	{
		$level = $this->calculateLevel($data);
		$data->addScalarExtraParameter('treeLevel', $level);
	}

    /**
     * @param \modules\miscellaneous\lib\TreeItem $data
     * @return int
     */
    function calculateLevel($data)
	{
		$level = 0;
		$parent = $data->getParent();
		if (!is_null($parent) && !is_null($parent->getExtraParameter('treeLevel')))
		{
			$level = $parent->getExtraParameter('treeLevel') + 1;
		}
		return $level;
	}
}

class BranchListingsCounter
{
	var $listing_count;

    /**
     * @param \modules\miscellaneous\lib\TreeItem $data
     */
    function handle($data)
	{
		$data->addArrayExtraParameter('branch_listings_count', $this->getListingCount());
	}
	
	function getListingCount()
	{
		return $this->listing_count;
	}
	
	function setListingCount($listing_count)
	{
		$this->listing_count = $listing_count;
	}
}
