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

class TreeParser {
	
	var $columns	= array();
	var $fields		= array();
	var $trees		= array();
	
	function __construct($columns) {
		$this->getRepeatedNames($columns);
		$this->_getRepeatedFields($this->columns);
		foreach($this->fields as $field)
			$this->trees[$field] = $this->_getFieldStructure($field);
	}
	
	function getTreeColumns() {
		return $this->trees;	
	}
	
	function getRepeatedNames($columns) {
		foreach($columns as $key => $property_name)
			if(strpos($property_name, '['))	$this->columns[$key] = $property_name;	
	}
	
	function _getRepeatedFields($column_names) {
		foreach($this->columns as $column_name)
			$this->fields[] = substr($column_name, 0, strpos($column_name, '[') );
		$this->fields = array_unique($this->fields);
	}
	
	function _getFieldStructure($field) {
		$result = array();
		foreach	($this->columns as $column_position => $column_name)
			if(strpos($column_name, $field) !== false)
				$result[$this->_getColumnLevel($column_name)] = $column_position;
		
		return $result;
	}
	
	function _getColumnLevel($column) {
		return substr($column, strpos($column, '[') + 1, -1);	
	}
}
?>
