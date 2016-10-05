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

class DateTimeFormatter
{
	private $format;

	public function getOutput($datetime)
	{
		return empty($datetime) ? "" : strftime($this->format, strtotime($datetime));
	}

	public function getInput($datetime)
	{
		$parsedDate = strptime($datetime, $this->format);
		$year = $parsedDate['tm_year'] + 1900;
		$month = $parsedDate['tm_mon'] + 1;
		$day = $parsedDate['tm_mday'];
		$hour = $parsedDate['tm_hour'];
		$min = $parsedDate['tm_min'];
		$sec = $parsedDate['tm_sec'];
		return sprintf("%s-%02s-%02s %02s:%02s:%02s", $year, $month, $day, $hour, $min, $sec);
	}

	public function isValid($datetime)
	{
		$parsedDate = strptime($datetime, $this->format);
		$timestamp = mktime($parsedDate['tm_hour'], $parsedDate['tm_min'], $parsedDate['tm_sec'], $parsedDate['tm_mon'] + 1, $parsedDate['tm_mday'], $parsedDate['tm_year'] + 1900);
		$dateToCompare = strftime($this->format, $timestamp);
		return isset($parsedDate['tm_year']) && isset($parsedDate['tm_mon']) && isset($parsedDate['tm_mday']) && isset($parsedDate['tm_hour']) && isset($parsedDate['tm_min']) && isset($parsedDate['tm_sec']) && $datetime == $dateToCompare;
	}

	public function setDateFormat($format)
	{
		$this->format = $format;
	}
}
