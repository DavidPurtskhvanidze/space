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


namespace lib\ORM\SearchEngine\SearchCriterions;

class InCriterion extends SearchCriterion
{
	function getSystemSQL()
	{
		if(!$this->isValid()) return null;

		$value = $this->getSQLValue();

		return "{$this->property->getFullColumnName()} IN ({$value})";
	}

	function isValid()
	{
		return !empty($this->value);
	}

	function _wrapValueWithApostrof($value){
		return "'" . $this->stringEscaper->escapeString($value) . "'";
	}

	function _wrapArrayWithApostrof($array){
		return array_map(array($this,"_wrapValueWithApostrof"), $array);
	}

	function getSQLValue()
	{
		$value 		= '';
		if (is_array($this->value))
			$value = join($this->_wrapArrayWithApostrof($this->value), ', ');
		if (empty($value)) $value = 'NULL';
		return $value;
	}
}
