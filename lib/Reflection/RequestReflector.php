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
class RequestReflector
{
	function get($item_key)
	{
		if(!preg_match("/^\[/", $item_key)) $item_key = "['".$item_key."']";
		return eval("return isset(\$_REQUEST$item_key) ? \$_REQUEST$item_key : null;");
	}
}
?>
