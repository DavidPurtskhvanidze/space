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

class FactoryReflector
{
	function setFactory(&$factory)
	{
		$this->factory = $factory;
	}
	
	function &create($name, $args = array())
	{
		$arguments = array();
		for ($i = 0; $i < count($args); $i++)
		{
			$arguments[] = '$args['.$i.']';
		}
		eval('$object = $this->factory->create' . $name . '(' . join(",", $arguments) . ');');
		return $object;
	}
}

?>
