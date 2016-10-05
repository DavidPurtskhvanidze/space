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

class ObjectPropertyDisplayValue implements \ArrayAccess
{
	private $objectProperty;

	private $nullObjectPropertyDisplayValue;

	public function setObjectProperty($objectProperty)
	{
		$this->objectProperty = $objectProperty;
	}
	public function exists()
	{
		return true;
	}
	public function isEmpty()
	{
		return $this->objectProperty->isEmpty();
	}
	public function isNotEmpty()
	{
		return !$this->isEmpty();
	}
	public function isSystem()
	{
		return $this->objectProperty->isSystem();
	}
	public function type()
	{
		return $this->objectProperty->getType();
	}
	public function value()
	{
		return $this->objectProperty->getDisplayValue();
	}

    public function rawValue()
    {
        return $this->objectProperty->getValue();
    }

	public function __toString()
	{
		return (string) $this->value();
	}
	public function offsetExists($offset)
	{
		return true;
	}
	public function offsetGet($offset)
	{
		if (method_exists($this, $offset))
		{
			return $this->$offset();
		}
		$displayValue = $this->objectProperty->getDisplayValue();
		if (
			(is_array($displayValue) && array_key_exists($offset, $displayValue))
			||
			($displayValue instanceof \ArrayAccess)
			)
		{
			return $displayValue[$offset];
		}
		$this->nullObjectPropertyDisplayValue->setObjectPropertyId($offset);
		return $this->nullObjectPropertyDisplayValue;
	}
	public function offsetSet($offset, $value)
	{
		throw new \Exception("This object is read only");
	}
	public function offsetUnset($offset)
	{
		throw new \Exception("This object is read only");
	}

	public function setNullObjectPropertyDisplayValue($nullObjectPropertyDisplayValue) {
		$this->nullObjectPropertyDisplayValue = $nullObjectPropertyDisplayValue;
	}

}
