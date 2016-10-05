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


class RatingType extends Type
{	
	function __construct($property_info)
	{
		parent::__construct($property_info);
		$this->default_template = 'rating.tpl';
	}

	function getPropertyVariablesToAssignTypeSpecific()
	{
		return $this->getRating()->getPropertyVariablesToAssign();
	}

	function getValue()
	{
		return $this->getRating()->getValue();
	}

	function getDisplayValue()
	{
		return $this->getRating()->getPropertyVariablesToAssign();
	}
	
	private $rating = null;

	private function getRating()
	{
		if (is_null($this->rating)) 
		{
			$this->rating = \App()->ObjectMother->createRatingManager($this->property_info)->createRating($this->object_sid, $this->property_info['sid']);
		}
		return $this->rating;
	}
	
	public function getColumnDefinition()
	{
		return 'VARCHAR(15)';
	}

	public function isSaveIntoDB() {return false;}
}
