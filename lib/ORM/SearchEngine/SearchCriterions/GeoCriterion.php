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

class GeoCriterion extends SearchCriterion
{
	function getSystemSQL()
	{
		if(!$this->isValid()) return null;
		$x = "69.1 * (l.`latitude` - c.`latitude`)";
		$y = "69.1 * (l.`longitude` - c.`longitude`) * COS(c.`latitude` / 57.3)";
		$location = $this->stringEscaper->escapeString($this->value['location']);
		$multiplicator = ($this->radius_search_unit == 'kilometers') ? 1.60934 : 1;
		return "{$this->property->getFullColumnName()} IN (SELECT l.`name` FROM `core_locations` l inner join `core_locations` c on c.`name`='".$location."' AND SQRT(POW($x,2) + POW($y,2)) * $multiplicator <= ".$this->value['radius'].")";
	}

	function isValid()
	{
		return (!empty($this->value['radius']) && !empty($this->value['location']) && is_numeric($this->value['radius']));
	}

	function getValue()
	{
		return $this->value;
	}
}
