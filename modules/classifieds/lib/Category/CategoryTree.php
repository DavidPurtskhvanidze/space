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

class CategoryTree extends \lib\ORM\Types\Tree\Tree implements \core\IService
{
	function init()
	{
		parent::init($this->_getInfoFromDB(), '\modules\classifieds\lib\Category\CategoryNode');
		$this->_correctListingQuantity();
		$this->root->setPath(null);
		$this->root->setLevel(0);
	}

	function _getInfoFromDB()
	{
		$table_prefix = 'classifieds_categories';
		$data = \App()->DB->query("SELECT *, `name` AS caption FROM `$table_prefix` ORDER BY `order` ");
		return $data;
	}
	
	function getHashedDataFromDB($query, $keycolumn, $valuecolumn)
	{
		$result = [];
		$rows = \App()->DB->query($query);
		if (count($rows) > 0) foreach ($rows as $row) $result[$row[$keycolumn]] = $row[$valuecolumn];
		return $result;
	}

	function _correctListingQuantity()
	{
		$this->root->setListingQuantity();
	}

	function getCategorySidByPath($category_path)
	{
		$category_sid = null;
		$sid = \App()->CategoryManager->getRootId();
		if ( empty($category_path) ) return $sid;
		$node = $this->root->getNode($sid);
		while( !is_null($node) && $id = array_shift($category_path) )
		{
			$node = $node->getChildById($id);
			if ($node) $category_sid = $node['sid'];
		}
		return $category_sid;
	}

	function getSidByParentAndName($parent_sid, $name)
	{
		if (!isset($this->nodes[$parent_sid])) return -1;
		foreach($this->nodes[$parent_sid]->getChildren() as $item) if ($item['name'] == $name) return $item['sid'];
		return -1;
	}

	public function getIdsOfParentsFor($category_sid)
	{
		$node = $this->getNode($category_sid);
		if (is_null($node)) return [];
		$path = [];
		for ($node = $node; !is_null($node); $node = $node->getParent()) $path[] = $node->getId();
		return array_reverse($path);
	}

	public function getCategoryBranches($category_sid)
	{
		$result = [];
		$result[] =  $category_sid;
		$childes = $this->getChildren($category_sid);

		$childesIterator = new \RecursiveArrayIterator($childes);
		foreach(new \RecursiveIteratorIterator($childesIterator) as $key => $val) {
			if ($key == 'sid')
			{
				$result[] = $val;
			}
		}
		return $result;
	}
}
