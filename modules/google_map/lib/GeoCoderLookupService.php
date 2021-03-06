<?php
/**
 *
 *    Module: google_map v.7.4.0-1, (c) WorksForWeb 2005 - 2015
 *
 *    Package: google_map-7.4.0-1
 *    Tag: tags/7.4.0-1@19060, 2015-12-14 12:48:53
 *
 *    This file is part of the 'google_map' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\google_map\lib;

class GeoCoderLookupService
{
	var $googleMapAPI;
	
	function getGeoCode($address)
	{
		if (($coords = $this->googleMapAPI->geoGetCoords($address)) != false)
		{
			$coords['lat'] = floatval_ignore_locale($coords['lat']);
			$coords['lon'] = floatval_ignore_locale($coords['lon']);
			return array('latitude' => $coords['lat'], 'longitude' => $coords['lon']);
		}
		return null;
	}
	
	function setGoogleMapAPI($googleMapAPI)
	{
		$this->googleMapAPI = $googleMapAPI;
	}
}
