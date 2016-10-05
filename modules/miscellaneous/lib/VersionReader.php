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


namespace modules\miscellaneous\lib;

class VersionReader
{
	const PACKAGE_INFO_URL = 'http://demo.worksforweb.com/iLister/product_version.txt';

	public function getVersion()
	{
		$version = \App()->CacheManager->getData('version', 'version');
		if (is_null($version))
		{
			$version = $this->getVersionFromUrl();
			if (is_null($version))
			{
				\App()->CacheManager->updateData('version', 'version', $version);
			}
		}

		return $version;
	}

	private function getVersionFromUrl()
	{
		$chanel = curl_init(self::PACKAGE_INFO_URL);
		curl_setopt($chanel, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($chanel);
		$responseStatus = curl_getinfo($chanel, CURLINFO_HTTP_CODE);
		curl_close($chanel);

		return $responseStatus === 200 ? $response : null;
	}
}
