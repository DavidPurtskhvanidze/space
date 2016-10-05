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


namespace modules\classifieds\lib;

class SendListingInfoController {

	var $listing_id	= null;
	var $submitted_data;

	function __construct($input_data) {
		$this->listing_id = isset($input_data['listing_id']) ? $input_data['listing_id'] : null;
		$this->submitted_data = $input_data;
	}

	function isListingSpecified() {
		$listing = ListingManager::getObjectBySID($this->listing_id);
		return !empty($this->listing_id) && !empty($listing);
	}

	function isDataSubmitted() {
		return isset($this->submitted_data['is_data_submitted']);
	}

	function getData() {
		$listing 			= ListingManager::getObjectBySID($this->listing_id);
		$listing_structure 	= ListingManager::createTemplateStructureForListing($listing);
		return array('listing' => $listing_structure, 'submitted_data' => $this->submitted_data);
	}

	function getListingID() {
		return $this->listing_id;
	}

}
?>
