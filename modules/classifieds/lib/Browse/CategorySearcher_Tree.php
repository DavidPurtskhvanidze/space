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

class CategorySearcher_Tree extends AbstractCategorySearcher
{
	private $ListingFieldTreeManager;

	public function setListingFieldTreeManager($ListingFieldTreeManager)
	{
		$this->ListingFieldTreeManager = $ListingFieldTreeManager;
	}
	
	function __construct($field){
		parent::__construct($field);
		$this->field = $field;
	}

	function _decorateItems($items, $request_data = Array()){
		$this->fieldSid = $this->getFieldSID($this->field['field']);
		$counts = $this->_getCountsByItems($items);
		$result = Array();
		$parentSid = $this->_getParentSid($request_data);
		$values = $this->ListingFieldTreeManager->getTreeValuesByParentSID($this->fieldSid, $parentSid);
		
		foreach($values as $sid => $caption){
			$count = $this->_getCountBySid($sid, $counts);
			$result[] = array('caption' => $caption, 'count' => $count);
		}
		return $result;
	}

	function _getCountsByItems($items){
		$res = Array();
		foreach($items as $item)
			$res[$item['caption']] = $item['count'];
		return $res;
	}

	function _getParentSid($request_data){
		$level = $this->field['treeLevel'];
		if($level === 1)
			return 0;
		else
			return $request_data[$this->field['field']]['tree'][$level - 2];
	}
	
	function _getCountBySid($sid, $counts){
		$branchIds = $this->ListingFieldTreeManager->getBranchIdsByParentSID($this->fieldSid, $sid);
		$count = 0;
		foreach($branchIds as $id) if(isset($counts[$id])) $count += $counts[$id];
		return $count;
	}
}
