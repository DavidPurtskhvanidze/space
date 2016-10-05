<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib\Location;

class LocationManager extends \lib\ORM\ObjectManager implements \core\IService
{

	protected $table_name = 'core_locations';

	public function init()
	{
		$this->dbManager = new \lib\ORM\ObjectDBManager();
	}

	public function createLocation($locationData)
	{
		$location = new Location();
		$location->setDetails($this->createLocationsDetails());
		$location->incorporateData($locationData);
		if (isset($locationData['sid'])) $location->setSid($locationData['sid']);
		return $location;
	}

	private function createLocationsDetails()
	{
		$details = new LocationDetails();
		$details->setDetailsInfo($details->getDetails());
		$details->setOrmObjectFactory(\App()->OrmObjectFactory);
		$details->buildProperties();
		return $details;
	}

	function saveLocation($location)
	{
		$this->dbManager->saveObject($location);
	}
	
	function getLocationsInfo()
	{
		$locations = \App()->DB->query("SELECT * FROM `{$this->table_name}` ORDER BY `sid`");
		return $locations;
	}
	
	function deleteLocationBySID($location_sid) {
		
		return \App()->DB->query("DELETE FROM `{$this->table_name}` WHERE `sid` = ?n", $location_sid);
		
	}
	
	function deleteAllLocations() {
		
		return \App()->DB->query("TRUNCATE TABLE `{$this->table_name}`");
		
	}
	
	function doesLocationExist($location_name) 
	{
		if (empty($location_name)) return false;
		
		$exists = \App()->DB->query("SELECT * FROM `{$this->table_name}` WHERE `name` = ?s", $location_name);
		
		return !empty($exists);		
	}
	
	function getLocationBySid($sid)
	{
		return $this->createLocation(parent::getObjectInfoBySID($this->table_name, $sid));
	}

	function getLocationInfoBySid($sid)
	{
		return parent::getObjectInfoBySID($this->table_name, $sid);
	}

	function getLocationInfoByName($name)
	{
		return \App()->DB->getSingleRow("SELECT * FROM `{$this->table_name}` WHERE `name` = ?s", $name);
	}

    function addLocation($name, $longitude, $latitude)
    {
        return \App()->DB->query("INSERT INTO `core_locations` SET `name` = ?s, `longitude` = ?f, `latitude` = ?f", $name, $longitude, $latitude);
    }

    function updateLocation($name, $longitude, $latitude)
    {
        return \App()->DB->query("UPDATE `core_locations` SET `longitude` = ?f, `latitude` = ?f WHERE `name` = ?s", $longitude, $latitude, $name);
    }
}

