<?php
/**
 *
 *    Module: I18N v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: I18N-7.5.0-1
 *    Tag: tags/7.5.0-1@19784, 2016-06-17 13:19:28
 *
 *    This file is part of the 'I18N' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\I18N\lib\Formatters;

class IntFormatter
{
	var $thousands_separator;
	
	function getOutput($value)
	{
        if (!is_numeric($value)) return NULL;
		return number_format($value, 0, ',', $this->thousands_separator);
	}
	
	function getInput($value)
	{
		$value = str_replace($this->thousands_separator, '', $value);
		return $value;
	}
	
	function isValid($value)
	{
		return preg_match("/^[+-]?\d+(\\" . $this->thousands_separator . "\d{3})*$/", $value);
	}
	
	function setThousandsSeparator($thousands_separator)
	{
		$this->thousands_separator = $thousands_separator;
	}
}

?>
