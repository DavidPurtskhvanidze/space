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


namespace modules\classifieds\lib\Listing;

class ImproperContentListing implements \modules\miscellaneous\lib\IReportImproperContentObjectType
{
	public function getType()
	{
		return 'listing';
	}

	public function doesObjectExist($objectSid)
	{
		return \App()->ListingManager->doesListingExist($objectSid);
	}

	public function getMessageTemplateName()
	{
		return 'email_template:admin_report_improper_listing_content';
	}

	public function getMessageParameters($objectSid)
	{
		return array('listing' => \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter(\App()->ListingManager->getObjectBySid($objectSid)));
	}
}
