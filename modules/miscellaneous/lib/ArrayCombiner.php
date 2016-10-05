<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

class ArrayCombiner
{
	function combine($keys, $values)
	{
		$res = array();
		$keys = array_values($keys);
		$values = array_values($values);
		foreach ($keys as $k => $v)
		{
			$res[$v] = isset($values[$k])? $values[$k]: null;
		}
		return $res;
	}
}

?>
