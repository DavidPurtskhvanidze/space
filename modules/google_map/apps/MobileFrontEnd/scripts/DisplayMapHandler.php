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


namespace modules\google_map\apps\MobileFrontEnd\scripts;

class DisplayMapHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $moduleName = 'google_map';
	protected $functionName = 'display_map';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$address = \App()->Request['address'];
		$defaultLatitude = \App()->Request['default_latitude'];
		$defaultLongitude = \App()->Request['default_longitude'];

		$geoCoderFactory = new \modules\google_map\lib\GeoCoderFactory();
		$geoCode = $geoCoderFactory->createGeoCoderWithCache()->getGeoCode($address);

		if (!is_null($geoCode))
		{
			$templateProcessor->assign('latitude', $geoCode['latitude']);
			$templateProcessor->assign('longitude', $geoCode['longitude']);
			$templateProcessor->display('google_map.tpl');
		}
		elseif (!is_null($defaultLatitude) && !is_null($defaultLongitude))
		{
			$templateProcessor->assign('latitude', $defaultLatitude);
			$templateProcessor->assign('longitude', $defaultLongitude);
			$templateProcessor->display('google_map.tpl');
		}
	}
}
