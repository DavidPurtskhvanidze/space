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

class RemoveListingFromComparisonAction
{
	var $dataSource;
	var $comparisonTable;
	var $errors = array();
	
	function perform()
	{
		$this->comparisonTable->removeListing($this->dataSource->get('listing_id'));
	}
	
	function canPerform()
	{
		$listingId = $this->dataSource->get('listing_id');
		if (empty($listingId))
		{
			$this->errors[] = 'LISTING_ID_NOT_SPECIFIED';
			\App()->ErrorMessages->addMessage('LISTING_ID_NOT_SPECIFIED');
		}
		return empty($this->errors);
	}
	
	function getErrors()
	{
		return $this->errors;
	}
	
	function setDataSource(&$dataSource)
	{
		$this->dataSource = $dataSource;
	}
	
	function setListingComparisonTable($comparisonTable)
	{
		$this->comparisonTable = $comparisonTable;
	}
}
