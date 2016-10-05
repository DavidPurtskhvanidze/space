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


namespace lib\ORM\Types;


class TreeType extends Type
{
	var $tree_values = array();
	var $tree_depth = null;
	var $display_value = array();

	function __construct($property_info)
	{
		parent::__construct($property_info);
		if(isset($property_info['tree_values']))
		{
			$this->tree_values = $property_info['tree_values'];
			$this->nodes = array();
			foreach ($this->tree_values as $v)
			{
				$this->nodes += $v;
			}
		}

		if(isset($property_info['tree_depth']))
		{
			$this->tree_depth = $property_info['tree_depth'];
		}

		$this->default_template = 'tree.tpl';

		$value = isset($this->property_info['value']) ? $this->property_info['value'] : null;
		$this->setValue($value);
	}
	
	function setValue($value)
	{
		if (is_array($value))
		{
			$this->property_info['value'] = $value;
			$tree_item_sid = $this->_getLastNotEmptyValueFromArray($value);
			$this->display_value = $this->_getDisplayValue($tree_item_sid);
		}
		elseif (!empty($value))
		{
			$path = array($value);
			$parent_sid = $this->_getParent($value);
			while ($parent_sid)
			{
				$path[] = $parent_sid;
				$parent_sid = $this->_getParent($parent_sid);
			}
			$this->property_info['value'] = array_reverse($path);
			$this->display_value = $this->_getDisplayValue($value);
		}
		else
		{
			$this->property_info['value'] = array();
			$this->display_value = array();
		}
	}

	function getPropertyVariablesToAssignTypeSpecific()
	{
		$levels_captions = $this->property_info['levels_captions'];
		$levels_captions = explode(",", $levels_captions);
		$levels_captions = array_map("trim", $levels_captions);

		return array(
						'id' 		  => $this->property_info['id'],
						'value'		  => $this->property_info['value'],
						'caption'		  => $this->property_info['caption'],
						'tree_values' => $this->tree_values,
						'tree_depth'  => $this->tree_depth,
						'display_value' => $this->display_value,
						'levels_captions' => $levels_captions,
					);
	}

	function getSQLValue()
	{
		$value = $this->property_info['value'];
		if (is_array($value)) $value = $this->_getLastNotEmptyValueFromArray($value);
		return empty($value) ? null : "'".\App()->DB->real_escape_string($value)."'";
	}
	
	function getKeywordValue()
	{
		if (is_array($this->display_value))
        {
            $tp = \App()->getTemplateProcessor();
            $tp->assign('display_value', $this->display_value);
            return $tp->fetch('field_types^display/tree.tpl');
        }
        else
        {
            return $this->display_value;
        }
	}

	function getValue()
	{
		if (is_array($this->display_value)) {

			$levels_ids = explode(",", $this->property_info['levels_ids']);

			$value = null;
			$display_value = array_values($this->display_value);

			while (count($levels_ids)) {

				$level_id = trim(array_shift($levels_ids));

				if (empty($level_id)) continue;

				$value[$level_id] = array_shift($display_value);

			}

			return $value;

		}
		else
			return $this->display_value;
	}

	function getDisplayValue()
	{
		return new TypeTreeDiplayer($this->property_info['id'], $this->property_info['levels_ids'], $this->display_value, $this->tree_depth);
	}

	static function getFieldExtraDetails() {
		
		return array(
		
			array(
				'id'		=> 'levels_ids',
				'caption'	=> 'Level IDs',
				'type'		=> 'string',
                'value'     => null,
				),
			array(
				'id'		=> 'levels_captions',
				'caption'	=> 'Level Captions',
				'type'		=> 'string',
                'value'     => null,
				),
		
		
		);
		
	}
	
	function getBranch($item_sid){

		$children = array();
	
		if (isset($this->tree_values[$item_sid])){
			
			$my_children = $this->tree_values[$item_sid];
			foreach ($my_children as $child_info){
				$child_sid = $child_info['sid'];
				array_push($children, $child_sid);
				$child_branch = $this->getBranch($child_sid);
				$children = array_merge($children, $child_branch);
				}
			}

		return $children;		
		
	}
	
	function getChildren($tree_item_sid) {

		$parent = $this->_getNode($tree_item_sid);
		$children = array();
		
		if($parent){
			
			foreach($this->tree_values as $node_values) foreach($node_values as $node) if ($node['parent_sid'] == $parent['sid']) $children[] = $node['sid'];
		}					
		return $children;
	}
	
	function _getParent($sid) {
		$node = $this->_getNode($sid);
		if($node) return $node['parent_sid'];
	}
	
	function _getDisplayValue($item_sid) {		
		$item_info = $this->_getNode($item_sid);
		$values = array();
		
		while (!is_null($item_info)) {			
			array_unshift($values, $item_info['caption']);	
			$item_info = $this->_getNode($item_info['parent_sid']);
		}
		
		return $values;		
	}

	function _getNode($sid) {
		return isset($this->nodes[$sid]) ? $this->nodes[$sid] : null;
	}
	
	public function isEmpty()
	{
		$value = $this->property_info['value'];
		if (empty($value)) return true;

		// $value MUST BE an array
		$lastValue = $this->_getLastNotEmptyValueFromArray($value);
		if (empty($lastValue)) return true;

		return $this->hasChild($lastValue);
	}

	function _getLastNotEmptyValueFromArray($array) {
		
		do {
				
			$value = array_pop($array);
			
		} while (empty($value) && count($array));
		
		return $value;
		
	}

	public function getOrderClause($fullColumnName)
	{
		$orderedTreeValues = array();
		foreach ($this->tree_values as $parentSid => $values)
		{

			usort($values, function ($value1, $value2) {
				return strcasecmp($value1['caption'], $value2['caption']);
			});
			$orderedTreeValues[$parentSid] = $values;
		}
		$orderedTreeValueSids = $this->getTreeValueSids($orderedTreeValues);
		$orderedTreeValueSidsString = join(", ", $orderedTreeValueSids);

		return "FIELD({$fullColumnName}, {$orderedTreeValueSidsString})";
	}

	private function getTreeValueSids($orderedTreeValues, $parentSid = 0)
	{
		if (empty($orderedTreeValues[$parentSid]))
		{
			return array();
		}
		$sids = array();
		foreach ($orderedTreeValues[$parentSid] as $value)
		{
			$sids[] = $value['sid'];
			$sids = array_merge($sids, $this->getTreeValueSids($orderedTreeValues, $value['sid']));
		}

		return $sids;
	}

	private function hasChild($lastValue)
	{
		$children = $this->getChildren($lastValue);
		return !empty($children);
	}
}

class TypeTreeDiplayer implements \ArrayAccess
{
	private $value;
	private $propertyId;
	private $treeDepth;
	
	public function __construct($propertyId, $levelIds, $value, $treeDepth)
	{
		$this->propertyId = $propertyId;
		$keys = explode("," , $levelIds);
		$keys = array_map("trim", $keys);
		while (count($keys) < count($value) || count($keys) < $treeDepth)
		{
			$keys[] = count($keys) + 1;
		}
		while (count($value) < count($keys))
		{
			$value[] = null;
		}
		$this->value = array_combine($keys, $value);
		$this->treeDepth = $treeDepth;
	}
	public function offsetGet($index)
	{
		if (!array_key_exists($index, $this->value)) throw new \Exception("Illegal offset '$index' requested for '{$this->propertyId}'");
		return $this->value[$index];
	}
	public function offsetExists($index)
	{
		return isset($this->value[$index]);
	}
	public function offsetSet($index, $value)
	{
		throw new \Exception('This object is read only');
	}
	public function offsetUnset($index)
	{
		throw new \Exception('This object is read only');
	}
	public function __toString()
	{
		return implode(' ', $this->value);
	}
	public function getTreeDepth()
	{
		return $this->treeDepth;
	}
	public function getValue()
	{
		return $this->value;
	}
}

?>
