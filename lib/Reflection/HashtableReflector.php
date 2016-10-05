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


namespace lib\Reflection;

class HashtableReflector
{
	var $hashtable = array();
	
	function setHashtable($hashtable)
	{
		$this->hashtable = $hashtable;
	}
	function getHashtable()
	{
		return $this->hashtable;
	}
	
	function get($item_key)
	{
		if(!preg_match("/^\[/", $item_key))
			$item_key = "['".$item_key."']";
		return eval("return isset(\$this->hashtable$item_key) ? \$this->hashtable$item_key : null;");
	}
	
	function set($item_key, $value)
	{
		$this->hashtable[$item_key] = $value;
	}
}

?>
