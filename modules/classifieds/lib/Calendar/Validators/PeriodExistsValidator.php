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


namespace modules\classifieds\lib\Calendar\Validators;

class PeriodExistsValidator
{
	private $DB;
	public function setDB($DB)
	{
		$this->DB = $DB;
	}
	
	function isValid($value)
	{
		if(!is_array($value))
		{
			$ids = array();
			$ids[] = $value;
		}
		else
		{
			$ids = array_keys($value);
		}
		foreach($ids as $id)
		{
			$data = $this->DB->query("select `sid` from `classifieds_listing_field_calendar` where `sid` = ?n", intval($id));
			if(empty($data))
			{
				return false;
			}
		}
		return true;
	}
}

?>
