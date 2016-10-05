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


namespace lib\Actions;

class StubAction
{
	var $message;
	
	function __construct($message)
	{
		$this->message=$message;
	}
	
	function perform()
	{
		if ($this->message) echo $this->message;
		else echo "StubAction perfomed";
	}
}

?>
