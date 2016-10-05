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

class CategorySearcherFactory
{
	function __construct()
	{
		$this->map = Array(
			'tree' => 'create_CategorySearcher_Tree',
			'list' => 'create_CategorySearcher_List',
			'integer' => 'create_CategorySearcher_Value',
			'string' => 'create_CategorySearcher_Value',
			'geo' => 'create_CategorySearcher_Value',
		);
	}
	
	function getCategorySearcher($field, $category_sid = 0)
	{
		$name = $field['field'];
		
		$searcher = null;
		if($name == 'category_sid')
		{
			$searcher = new CategorySearcher_Type($field);
			$searcher->init();
		}
		else
		{
			$type = $field['type'];
			if($this->_existsCategorySearcher($type))
			{
	 			$searcher =$this->_create_CategorySearcher($type, $field);
			}
		}
		if(is_null($searcher))
		{
			die("CategorySearcher for the '" . $type . "' type does not exist");
		}
		$searcher->setCategorySid($category_sid);
		return $searcher;
	}
	
	function _existsCategorySearcher($type)
	{
		return isset($this->map[$type]);
	}
	function &_create_CategorySearcher($type, $field)
	{
		$methodName = $this->map[$type];
	 	$searcher = $this->$methodName($field);
	 	return $searcher;
	}
	public function create_CategorySearcher_Tree($field)
	{
				$instance = new CategorySearcher_Tree($field);
		$instance->setListingFieldTreeManager(\App()->ListingFieldTreeManager);
		return $instance;
	}
	public function create_CategorySearcher_Value($field)
	{
				$instance = new CategorySearcher_Value($field);
		return $instance;
	}
	public function create_CategorySearcher_List($field)
	{
				$instance = new CategorySearcher_List($field);
		$instance->setListingFieldListItemManager(\App()->ObjectMother->createListingFieldListItemManager());
		return $instance;
	}
}
