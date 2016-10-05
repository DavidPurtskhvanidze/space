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


namespace lib\ORM\Rating;
class Rating
{
	var $fieldSid = null;
	var $objectSid = null;
	var $manager = null;
	
	function setObjectSid($objectSid)
	{
		$this->objectSid = $objectSid;
	}

	function setFieldSid($fieldSid)
	{
		$this->fieldSid = $fieldSid;
	}

	function setManager(&$manager)
	{
		$this->manager = $manager;
	}

	function getValue()
	{
		$data = $this->manager->getRating($this->objectSid, $this->fieldSid);
		return $data['rating'];
	}

	function &getPropertyVariablesToAssign()
	{
		$data = $this->manager->getRating($this->objectSid, $this->fieldSid);
		$res = array
		(
			'object_sid' => $this->objectSid, 
			'object_type' => $this->manager->getObjectType(), 
			'field_sid' => $this->fieldSid,
			'rating' => $data['rating'],
			'count' => $data['count'],
		);
		return $res;
		
	}
}
?>
