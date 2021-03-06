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

class GeoCoderFactory
{
	public function createGeoCoder()
	{
		$instance = new GeoCoder();
		$instance->setLookupService($this->createGeoCoderLookupService());
		return $instance;
	}

	public function createGeoCoderWithCache()
	{
		return new GeoCoderWithCache($this->createGeoCoder());
	}

	public function createGeoCoderLookupService()
	{
		$instance = new GeoCoderLookupService();
		$instance->setGoogleMapAPI($this->createGoogleMapAPI());
		return $instance;
	}

	public function createGoogleMapAPI()
	{
		require_once("GoogleMap/GoogleMapAPI.class.php");
		$instance = new \GoogleMapAPI();
		return $instance;
	}
}
