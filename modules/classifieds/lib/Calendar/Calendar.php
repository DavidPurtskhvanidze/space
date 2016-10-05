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

class Calendar
{
	var $datasource = null;
	var $generator = null;

	function setDatasource(&$datasource)
	{
		$this->datasource = $datasource;
	}

	function setGenerator(&$generator)
	{
		$this->generator = $generator;
	}
	
	function &getData($from, $to)
	{
		$dates = $this->generator->getDates($from, $to);
		foreach(array_keys($dates) as $key)
		{
			$day = $dates[$key];
			$status = $this->datasource->getStatus($day['date']);
			$day['status'] = $status;
		}
		
		return $dates;
	}
	
	function &getPropertyVariablesToAssign()
	{
		$d1 = getdate();
		$d2 = getdate(strtotime("+3 month"));
		$data = $this->getData("{$d1['year']}-{$d1['mon']}-01", "{$d2['year']}-{$d2['mon']}-01 -1 day");
		$months = array();
		$currentDate = getDate();
		foreach(array_keys($data) as $key)
		{
			$day = $data[$key];
			if(!isset($months[$day['month']]))
			{
				$months[$day['month']] = array();
			}
			$months[$day['month']][] = $day;
			
			if("{$currentDate['year']}-{$currentDate['mon']}-{$currentDate['mday']}" === "{$day['year']}-{$day['mon']}-{$day['mday']}")
			{
				$day['is_current'] = true;
			}
		}
		$res = array
		(
			'listing_sid' => $this->datasource->listingSid, 
			'field_sid' => $this->datasource->fieldSid,
			'calendarData' => &$data,
			'months' => &$months,
			'calendar' => $this->datasource->data,
		);
		return $res;
		
	}
}
