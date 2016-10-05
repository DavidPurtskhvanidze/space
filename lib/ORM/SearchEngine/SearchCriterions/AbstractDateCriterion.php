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

class AbstractDateCriterion extends SearchCriterion{

	var $comparisson_sign = null;

	function getSystemSQL()
	{
		if(!$this->isValid()) return null;
		$date_string = $this->_getValidSQLValue();

		return "{$this->property->getFullColumnName()} {$this->comparisson_sign} $date_string ";
	}

	function isValid()
	{
		if (empty($this->value)) return false;
		return $this->I18N->isValidDate($this->value);
	}

	function _getValidSQLValue(){
		return null;
	}
}
