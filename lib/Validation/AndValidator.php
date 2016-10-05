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


namespace lib\Validation;

class AndValidator
{
	var $validators = array();
	
	function add(&$validator)
	{
		$this->validators[] =$validator;
	}
	
	function isValid($value)
	{

		for($i = 0 ; $i < count($this->validators) ; $i++){
			$validator = $this->validators[$i];
			if(!$validator->isValid($value))
				return false;
		}
		return true;
	}
}

?>
