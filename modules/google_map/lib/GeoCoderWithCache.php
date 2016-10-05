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

class GeoCoderWithCache extends GeoCoderDecorator
{
	public function getGeoCode($address)
	{
		$geoCode = $this->getGeoCodeFromCache($address);
		if (empty($geoCode))
		{
			$geoCode = parent::getGeoCode($address);
			if (!is_null($geoCode))
			{
				$this->setGeoCodeToCache($address, $geoCode['latitude'], $geoCode['longitude']);
			}
		}
		return $geoCode;
	}

	private function getGeoCodeFromCache($address)
	{
		$sqlResult = \App()->DB->query("SELECT * FROM `google_map_geocodes` WHERE `address` = ?s", $address);
		return array_pop($sqlResult);
	}

	private function setGeoCodeToCache($address, $latitude, $longitude)
	{
		\App()->DB->query("INSERT INTO `google_map_geocodes` SET `address` = ?s, `latitude` = ?f, `longitude` = ?f", $address, $latitude, $longitude);
	}
}
