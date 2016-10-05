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

class MapCriterion extends SearchCriterion
{
	function getSystemSQL()
	{
		if(!$this->isValid()) return null;
		$x = "69.1 * (".$this->value['latitude']." - `latitude`)";
		$y = "69.1 * (".$this->value['longitude']." - `longitude`)";
		return "{$this->property->getFullColumnName()} IN (SELECT `name` FROM `core_locations` WHERE SQRT(POW($x,2) + POW($y,2)) <= ".$this->value['radius']."/1.60934)";
	}

	function isValid()
	{
		return (!empty($this->value['radius']) && is_numeric($this->value['radius']) && 
                !empty($this->value['latitude']) && is_numeric($this->value['latitude']) &&
                !empty($this->value['longitude']) && is_numeric($this->value['longitude']));
	}

	function getValue()
	{
		return $this->value;
	}
}
