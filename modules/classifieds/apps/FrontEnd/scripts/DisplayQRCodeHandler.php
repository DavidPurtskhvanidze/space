<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\apps\FrontEnd\scripts;

class DisplayQRCodeHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display QR Code of Listing';
	protected $moduleName = 'classifieds';
	protected $functionName = 'display_qr_code';
	protected $rawOutput = true;

	public function respond()
	{
        $listing_id = isset($_REQUEST['listing_id']) ? $_REQUEST['listing_id'] : 0;
		if (is_null($listing = \App()->ListingManager->getObjectBySID($listing_id)))
		{
			header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');// no such listing
            return;
		}

		$listingDisplayer = new \modules\classifieds\lib\Listing\ListingDisplayer();
        $listingDisplayer->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);
        $listing = $listingDisplayer->wrapListing($listing);

		if (\App()->doesAppExist('MobileFrontEnd'))
		{
			$siteUrl = \App()->SystemSettings->getSettingForApp('MobileFrontEnd', 'SiteUrl');
			$listingDetailsPageUri = \App()->CustomSettings->getSettingValue('uri_of_listing_details_page_on_mobile_frontend');;
		}
		else
		{
			$siteUrl = \App()->SystemSettings['SiteUrl'];
			$listingDetailsPageUri = \App()->PageRoute->getPageURIById('listing');
		}
		$url = rawurlencode($listing->getObjectUrlSeoData());
		$url = str_replace('%2F','/', $url);
		$url = str_replace('%20','-', $url);
       	$url = $siteUrl . $listingDetailsPageUri . $listing_id . '/' . $url . '.html';

        $this->generateQRCode($url);
    }
	private function generateQRCode($url)
	{
        header('Content-Type: image/png');
        echo \App()->QRCodeGenerator->generateQRCode($url);
	}
}
