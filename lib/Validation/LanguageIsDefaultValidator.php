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

class LanguageIsDefaultValidator
{
	var $context;
	
	function setContext(&$context)
	{
		$this->context = $context;
	}
	
	function isValid($value)
	{
		return $value == $this->context->getDefaultLang();
	}
}

?>
