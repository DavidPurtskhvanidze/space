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

class ReflectionFactory
{
	function createHashtableReflector($hashtable)
	{
		$reflector = new HashtableReflector();
		$reflector->setHashtable($hashtable);
		return $reflector;
	}
	
	function createFactoryReflector($factory)
	{
		$reflector = new FactoryReflector();
		$reflector->setFactory($factory);
		return $reflector;
	}
	
	function createConstantReflector($value)
	{
		$reflector = new ConstantReflector();
		$reflector->setValue($value);
		return $reflector;
	}
}
