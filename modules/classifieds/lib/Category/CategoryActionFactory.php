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

class CategoryActionFactory
{
	function createAction($action_name, $params)
	{
		$category_sid = isset($params['relocating_category_sid']) ? $params['relocating_category_sid'] : null;
		$dest_category_sid = isset($params['dest_category_sid']) ? $params['dest_category_sid'] : null;
		$position = isset($params['position']) ? $params['position'] : null;
		
		switch ($action_name)
		{
			case 'move':
				$action = $this->createMoveAction($position, $category_sid, $dest_category_sid);
				break;
			case 'set_child_of':
				$action = $this->createSetChildOfAction($category_sid, $dest_category_sid);
				break;
			default:
				$action = $this->createNullAction();
				break;
		}
		return $action;
	}
	
	function createMoveAction($position, $category_sid, $dest_category_sid)
	{	
		if ($position == 'before')
		{
			$action = $this->createMoveBeforeAction($category_sid, $dest_category_sid);
		}
		elseif ($position == 'after')
		{
			$action = $this->createMoveAfterAction($category_sid, $dest_category_sid);
		}
		else
		{
			$action = $this->createNullAction();
		}
		return $action;
	}
	
	function createSetChildOfAction($category_sid, $dest_category_sid)
	{
		$action = new SetCategoryChildOfAction();
		$action->setCategorySID($category_sid);
		$action->setDestinationCategorySID($dest_category_sid);
		$action->setCategoryManager($this->categoryManager);
		$action->setListingManager($this->listingManager);
		$action->setTreeWalker($this->treeWalker);
		$action->setListingCountSummator($this->listingCountSummator);
		$action->setListingFieldManager($this->listingFieldManager);
		$action->setListingFieldCollector($this->listingFieldCollector);
		return $action;
	}
	
	function createMoveBeforeAction($category_sid, $dest_category_sid)
	{
		$action = new MoveCategoryBeforeAction();
		$action->setCategoryManager($this->categoryManager);
		$action->setCategorySID($category_sid);
		$action->setDestinationCategorySID($dest_category_sid);
		return $action;
	}
	
	function createMoveAfterAction($category_sid, $dest_category_sid)
	{
		$action = new MoveCategoryAfterAction();
		$action->setCategoryManager($this->categoryManager);
		$action->setCategorySID($category_sid);
		$action->setDestinationCategorySID($dest_category_sid);
		return $action;
	}
	
	function createNullAction()
	{
		$action = \App()->ObjectMother->createNullAction();
		return $action;
	}
	
	function setCategoryManager($categoryManager)
	{
		$this->categoryManager = $categoryManager;
	}
	
	function setListingManager($listingManager)
	{
		$this->listingManager = $listingManager;
	}
	
	function setTreeWalker($treeWalker)
	{
		$this->treeWalker = $treeWalker;
	}
	
	function setListingCountSummator($listingCountSummator)
	{
		$this->listingCountSummator = $listingCountSummator;
	}
	
	function setListingFieldManager($listingFieldManager)
	{
		$this->listingFieldManager = $listingFieldManager;
	}
	
	function setListingFieldCollector($listingFieldCollector)
	{
		$this->listingFieldCollector = $listingFieldCollector;
	}
}

?>
