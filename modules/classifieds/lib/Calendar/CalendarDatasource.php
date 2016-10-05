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

class CalendarDatasource
{
	var $data = null;
	var $dafault = null;
	var $fieldSid = null;
	var $listingSid = null;
	var $manager = null;

	function setCalendarManager(&$manager)
	{
		$this->manager = $manager;
	}

	function setListingSid($listingSid)
	{
		$this->listingSid = $listingSid;
	}

	function setFieldSid($fieldSid)
	{
		$this->fieldSid = $fieldSid;
	}

	function setDefaultStatus($status)
	{
		$this->dafault = $status;
	}

	function loadData()
	{
		if(is_null($this->data))
		{
			$this->data = $this->manager->getPeriods($this->listingSid, $this->fieldSid);
		}
	}

	function getStatus($date)
	{
		$this->loadData();
		$timestamp = strtotime($date);
		foreach(array_keys($this->data) as $key)
		{
			$period = $this->data[$key];
			$fromTimestamp = strtotime($period['from']);
			$toTimestamp = strtotime($period['to']);
			if($timestamp >= $fromTimestamp && $timestamp <= $toTimestamp)
			{
				return $period['status'];
			}
		}
		return $this->dafault;
	}
}
