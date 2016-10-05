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

class LanguageIsActiveValidator
{
	var $langDataSource;
	var $languageExistsValidator;
	
	function setLanguageDataSource($langDataSource)
	{
		$this->langDataSource = $langDataSource;
	}
	
	function setLanguageExistsValidator($validator)
	{
		$this->languageExistsValidator = $validator;
	}
	
	function isValid($value)
	{
		if (!$this->languageExistsValidator->isValid($value)) return false;
		
		$langData = $this->langDataSource->getLanguageData($value);
		return $langData->getActive();
	}	
}

?>
