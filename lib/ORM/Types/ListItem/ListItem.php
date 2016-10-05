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


namespace lib\ORM\Types\ListItem;

class ListItem {
	
	var $field_sid;
	var $value;
	var $sid;
	var $rank;
	
	function setValue($value) {
		$this->value = $value;
	}
	
	function getValue() {
		return $this->value;
	}
	
	function setFieldSID($field_sid) {
		$this->field_sid = $field_sid;
	}
	
	function getFieldSID() {
		return $this->field_sid;
	}
	
	function setSID($sid) {
		$this->sid = $sid;
	}
	
	function getSID() {
		return $this->sid;
	}

	function setRank($rank) {
		$this->rank = $rank;
	}
	
	function getRank() {
		return $this->rank;
	}
}

?>
