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


namespace lib\Criteria;

class LessEqualThenCriterion
{
	var $value1;
	var $value2;
	
	function setValue1($value1)
	{
		$this->value1 = $value1;
	}
	function setValue2($value2)
	{
		$this->value2 = $value2;
	}
	function isTrue()
	{
		return $this->value1 <= $this->value2;
	}
}

?>
