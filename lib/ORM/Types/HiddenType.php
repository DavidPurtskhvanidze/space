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

class HiddenType extends Type {
	
	function __construct($property_info) {
		parent::__construct($property_info);
		$this->sql_type 		= 'SIGNED';
		$this->default_template = 'hidden.tpl';
	}

	function getPropertyVariablesToAssignTypeSpecific() {
		return array(
						'id'		=> $this->property_info['id'],
						'value'		=> $this->property_info['value'],
					);
	}

	static function getFieldExtraDetails() {
		return array();		
	}

    function getKeywordValue() {
		return $this->property_info['value'];
	}
	
}
?>
