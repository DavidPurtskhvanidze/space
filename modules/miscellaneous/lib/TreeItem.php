<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

class TreeItem
{
	var $id;
	var $parent_id;
	var $extra_parameters = array();
	
	var $parent;
	var $children = array();
	
	function setID($id)
	{
		$this->id = $id;
	}
	
	function setParentID($parent_id)
	{
		$this->parent_id = $parent_id;
	}
	
	function addScalarExtraParameter($extra_name, $value_name)
	{
		$this->extra_parameters[$extra_name] = $value_name;
	}
	
	function addArrayExtraParameter($extra_name, $value_name)
	{
		if (!isset($this->extra_parameters[$extra_name]))
		{
			$this->extra_parameters[$extra_name] = array();
		}
		$this->extra_parameters[$extra_name][] = $value_name;
	}
	
	function setParent($parent)
	{
		$this->parent = $parent;
	}
	
	function addChild($child)
	{
		$this->children[] = $child;
	}
	
	function getID()
	{
		return $this->id;
	}
	
	function getParentID()
	{
		return $this->parent_id;
	}
	
	function getExtraParameter($parameter_name)
	{
		return isset($this->extra_parameters[$parameter_name]) ? $this->extra_parameters[$parameter_name] : null;
	}

    /**
     * @return TreeItem
     */
    function getParent()
	{
		return $this->parent;
	}
	
	function getChildren()
	{
		return $this->children;
	}

	public function hasChildren()
	{
		return !empty($this->children);
	}
	public function hasParent()
	{
		return !empty($this->parent);
	}

    public function unsetExtraParameter($extraParameterKey)
    {
        unset($this->extra_parameters[$extraParameterKey]);
    }
}
