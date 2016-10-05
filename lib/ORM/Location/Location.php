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


namespace lib\ORM\Location;

class Location {
	
	var $sid;
	
	var $name;
	
	var $longitude;
	
	var $latitude;
	
	function __construct($location_info = null) {
		
		$this->name = isset($location_info['name']) ? $location_info['name'] : null;
			
		$this->longitude = isset($location_info['longitude']) ? $location_info['longitude'] : null;
		
		$this->latitude = isset($location_info['latitude']) ? $location_info['latitude'] : null;
		
	}
	
	function getInfo() {
		
		$info['name'] = $this->name;
		
		$info['longitude'] = $this->longitude;
		
		$info['latitude'] = $this->latitude;
		
		return $info;
		
	}
	
	function isDataValid() {
		
		$noErrors = true;
		
		
		if ($this->name == '')
		{
			\App()->ErrorMessages->addMessage('EMPTY_VALUE', array('fieldCaption' => 'Name'));
			$noErrors = false;
		}
		
		if ($this->longitude == '')
		{
			\App()->ErrorMessages->addMessage('EMPTY_VALUE', array('fieldCaption' => 'Longitude'));
			$noErrors = false;
		}
		elseif (!is_numeric($this->longitude))
		{
			\App()->ErrorMessages->addMessage('NOT_FLOAT_VALUE', array('fieldCaption' => 'Longitude'));
			$noErrors = false;
		}
		
		if ($this->latitude == '')
		{
			\App()->ErrorMessages->addMessage('EMPTY_VALUE', array('fieldCaption' => 'Latitude'));
			$noErrors = false;
		}
		elseif (!is_numeric($this->latitude))
		{
			\App()->ErrorMessages->addMessage('NOT_FLOAT_VALUE', array('fieldCaption' => 'Latitude'));
			$noErrors = false;
		}
		
		return $noErrors;
		
	}
	
	function setSID($location_sid) {
		
		$this->sid = $location_sid;
		
	}
	
	function getSID() {
		
		return $this->sid;
		
	}
	
}

?>
