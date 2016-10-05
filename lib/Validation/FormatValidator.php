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

class FormatValidator
{
	function setValidSymbols($validSymbols)
	{
		$this->validSymbols = $validSymbols;
	}
	function setRegex($regex)
	{
		$this->regex = $regex;
	}

	function isValid($format)
	{
		preg_match_all($this->regex, $format, $matches);
		foreach($matches[1] as $symbol)
		{
			if (empty($symbol) || strpos($this->validSymbols, $symbol) === false)
				return false;
		}
		return true;
	}
}

?>
