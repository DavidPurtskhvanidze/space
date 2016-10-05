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

class DateFormatter
{
	var $format;
	
	function getOutput($date)
	{
		return empty($date) ? "" : strftime($this->format, strtotime($date));
	}
	
	function getInput($date)
	{
		$parsed_date = strptime($date, $this->format);
		$year = $parsed_date['tm_year'] + 1900;
		$month = $parsed_date['tm_mon'] + 1;
		$day = $parsed_date['tm_mday'];
		return sprintf("%s-%02s-%02s", $year, $month, $day);
	}
	
	function isValid($date)
	{
		$parsed_date = strptime($date, $this->format);		
		$timestamp = mktime(0, 0, 0, $parsed_date['tm_mon'] + 1, $parsed_date['tm_mday'], $parsed_date['tm_year'] + 1900);
		$date_to_compare = strftime($this->format, $timestamp);
		return isset($parsed_date['tm_year']) && isset($parsed_date['tm_mon']) && isset($parsed_date['tm_mday']) && $date == $date_to_compare;
	}
	
	function setDateFormat($format)
	{
		$this->format = $format;
	}
}
?>
