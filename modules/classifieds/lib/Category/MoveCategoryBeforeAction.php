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

class MoveCategoryBeforeAction
{
	var $errors = array();
	
	function setCategoryManager(&$categoryManager)
	{
		$this->categoryManager = $categoryManager;
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
		if (!$this->categoryManager->doesCategoryExist($this->dest_category_sid))
		{
			$this->errors['NON_EXISTED_DESTINATION_CATEGORY'] = 'NON_EXISTED_DESTINATION_CATEGORY';
			\App()->ErrorMessages->addMessage('NON_EXISTED_DESTINATION_CATEGORY');
		}
		if ($this->categoryManager->getParentSid($this->category_sid) != $this->categoryManager->getParentSid($this->dest_category_sid))
		{
			$this->errors['NOT_SAME_PARENT'] = 'NOT_SAME_PARENT';
			$relocatingCategoryId = $this->categoryManager->getCategoryIDBySID($this->category_sid);
			$destinationCategoryId = $this->categoryManager->getCategoryIDBySID($this->dest_category_sid);
			\App()->ErrorMessages->addMessage('NOT_SAME_PARENT', array('relocatingCategoryId' => $relocatingCategoryId, 'destinationCategoryId' => $destinationCategoryId));
		}
		return empty($this->errors);
	}
	
	function perform()
	{
		$this->categoryManager->moveBefore($this->category_sid, $this->dest_category_sid);
	}
	
	function getErrors()
	{
		return $this->errors;
	}
}

?>
