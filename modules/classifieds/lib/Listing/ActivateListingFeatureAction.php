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

class ActivateListingFeatureAction
{
	var $listing;
	var $featureId;
	var $errors = array();

	function canPerform()
	{
		if (is_null($this->listing))
		{
			$this->errors[] = 'LISTING_DOES_NOT_EXIST';
			return false;
		}
		if (!$this->listing->isActive())
		{
			$this->errors[] = 'LISTING_IS_NOT_ACTIVE';
			return false;
		}
		return true;
	}
	
	function perform()
	{
		\App()->ListingFeaturesManager->activateFeatureForListing($this->listing, $this->featureId);
	}
	
	function getErrors()
	{
		return $this->errors;
	}
	
	function getPrice()
	{
		return \App()->ListingFeaturesManager->getPriceForFeature($this->listing,$this->featureId);
	}
	
	
	function getListing()
	{
			return $this->listing;
	}
	
	function setListing($listing)
	{
		$this->listing = $listing;
	}

	function setFeatureId($featureId)
	{
		$this->featureId = $featureId;
	}
}
