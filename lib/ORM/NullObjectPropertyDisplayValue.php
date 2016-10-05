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


namespace lib\ORM;


class NullObjectPropertyDisplayValue extends \lib\ORM\ObjectPropertyDisplayValue
{
	private $objectPropertyId;

	public function setObjectPropertyId($objectPropertyId)
	{
		$this->objectPropertyId = $objectPropertyId;
	}
	public function exists()
	{
		return false;
	}
	public function isEmpty()
	{
		return true;
	}
	public function isNotEmpty()
	{
		return false;
	}
	public function isSystem()
	{
		return false;
	}
	public function __toString()
	{
		return "<p class=\"error\">Invalid field id \"{$this->objectPropertyId}\" specified</p>";
	}
	public function value()
	{
		return null;
	}
	public function type()
	{
		return null;
	}
	public function offsetGet($offset)
	{
		if (method_exists($this, $offset))
			return $this->$offset();
		return "<p class=\"error\">Invalid field id \"{$this->objectPropertyId}\" specified</p>";
	}
}
