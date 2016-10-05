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


namespace lib\ORM\Types;

class ObjectType extends Type
{
	function __construct($property_info)
	{
		parent::__construct($property_info);
	}
	function getDisplayValue()
	{
		if (is_null($this->property_info['value'])) return null;
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this->property_info['value']);
	}

	public function getSQLValue()
	{
		return "'". \App()->DB->real_escape_string(serialize($this->property_info['value'])) ."'";
	}

	public function getValueForEncodingToJson()
	{
		/**
		 * @var \lib\ORM\Object $object
		 */
		$object = $this->property_info['value'];
		return is_null($object) ? null: $object->getValueForEncodingToJson();
	}

	public function setValue($value)
	{
		if (is_null($value) || is_object($value))
		{
			parent::setValue($value);
		}
		else
		{
			parent::setValue(@unserialize($value));
		}
	}
}
