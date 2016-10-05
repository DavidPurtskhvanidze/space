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

class FromBeforeToValidator
{
	var $reflector = null;
	function setReflector(&$reflector)
	{
		$this->reflector = $reflector;
	}
	function isValid()
	{
		$from = $this->reflector->get('from');
		$to = $this->reflector->get('to');
		if(empty($from) || empty($to))
		{
			return true;
		}
		
		$from = strptime($from, \App()->I18N->getRawDateFormat());
		$to = strptime($to, \App()->I18N->getRawDateFormat());
		$from = sprintf("%04s%02s%02s", $from['tm_year'] + 1900, $from['tm_mon'] + 1, $from['tm_mday']);
		$to = sprintf("%04s%02s%02s", $to['tm_year'] + 1900, $to['tm_mon'] + 1, $to['tm_mday']);
	
		return $from <= $to;
	}
}

?>
