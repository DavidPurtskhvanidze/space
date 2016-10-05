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

class LocationManager implements \core\IService
{
	public function init(){}

	function saveLocation($location)
	{
		$location_sid = $location->getSID();
		if (is_null($location_sid))
		{
			return \App()->DB->query("INSERT INTO `core_locations`(`name`, `longitude`, `latitude`) VALUES (?s, ?f, ?f)",
												$location->name, $location->longitude, $location->latitude, false);
		}
		else
		{
			return \App()->DB->query("UPDATE `core_locations` SET `name` = ?s, `longitude` = ?f, `latitude` = ?f WHERE `sid` = ?n",
												$location->name, $location->longitude, $location->latitude, $location->getSID());
		}
	}
	
	function getLocationsInfo()
	{
		$locations = \App()->DB->query("SELECT * FROM `core_locations` ORDER BY `sid`");
		return $locations;
	}
	
	function deleteLocationBySID($location_sid) {
		
		return \App()->DB->query("DELETE FROM `core_locations` WHERE `sid` = ?n", $location_sid);
		
	}
	
	function addLocation($name, $longitude, $latitude)
	{
		return \App()->DB->query("INSERT INTO `core_locations` SET `name` = ?s, `longitude` = ?f, `latitude` = ?f", $name, $longitude, $latitude);
	}

	function updateLocation($name, $longitude, $latitude)
	{
		return \App()->DB->query("UPDATE `core_locations` SET `longitude` = ?f, `latitude` = ?f WHERE `name` = ?s", $longitude, $latitude, $name);
	}
	
	function getLocationsInfoWithLimit($offset, $count)
	{
		$locations = \App()->DB->query("SELECT * FROM `core_locations` ORDER BY `sid` LIMIT $offset, $count");
		return $locations;
	}
	
	function getLocationNumber()
	{
		$number = \App()->DB->getSingleValue("SELECT count(*) FROM `core_locations`");
		return $number;
	}
	
	function deleteAllLocations() {
		
		return \App()->DB->query("TRUNCATE TABLE `core_locations`");
		
	}
	
	function doesLocationExist($location_name) 
	{
		if (empty($location_name)) return false;
		
		$exists = \App()->DB->query("SELECT * FROM `core_locations` WHERE `name` = ?s", $location_name);
		
		return !empty($exists);		
	}
	
	function getLocationInfoByName($location_name) {

		$location_info = \App()->DB->query("SELECT * FROM `core_locations` WHERE `name` = ?s", $location_name);
		
		if (empty($location_info)) {
			
			return null;
			
		} else {
			
			return array_pop($location_info);
			
		}
		
	}

	function getLocationInfoBySID($location_sid) {
		
		$location_info = \App()->DB->query("SELECT * FROM `core_locations` WHERE `sid` = ?n", $location_sid);
		
		if (empty($location_info)) {
			
			return null;
			
		} else {
			
			return array_pop($location_info);
			
		}
		
	}
	
}

?>
