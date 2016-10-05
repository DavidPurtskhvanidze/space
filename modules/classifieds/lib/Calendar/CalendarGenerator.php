<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib\Calendar;

class CalendarGenerator
{
	var $weekdays = array('Sunday' => 'sun', 'Monday' => 'mon', 'Tuesday' => 'tue', 'Wednesday' => 'wed', 'Thursday' => 'thu', 'Friday' => 'fri', 'Saturday' => 'sat');
	var $keys = array('mday', 'mon', 'year', 'weekday', 'month', 'weekday', 'wday');

	function &getDates($from, $to)
	{
		$fromTimestamp = strtotime($from);
		$toTimestamp = strtotime($to);
		$res = array();
		for($time = $fromTimestamp; $time <= $toTimestamp; $time += 24 * 60 * 60)
		{
			$res[] = $this->getDate($time);
		}
		return $res;
	}
	
	function &getDate($time)
	{
		$date = getdate($time);
		$res = array();
		foreach($this->keys as $key)
		{
			$res[$key] = $date[$key];
		}
		$res['date'] = "{$date['year']}-{$date['mon']}-{$date['mday']}";
		$res['weekday_short'] = $this->weekdays[$res['weekday']];

		return $res;
	}
}
