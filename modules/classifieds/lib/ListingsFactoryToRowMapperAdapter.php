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

class ListingsFactoryToRowMapperAdapter 
{
	private $listingFactory;
	private $extraPropertySetters;
	static private $listings = [];
	public function __construct($listingFactory)
	{
		$this->listingFactory = $listingFactory;
		$this->extraPropertySetters = array();
	}
	
	public function addListingExtraPropertySetter($extraPropertySetter)
	{
		$this->extraPropertySetters[] = $extraPropertySetter;
	}
	
	public function mapRowToObject($row)
	{
        if (!isset(self::$listings[$row['sid']]))
        {
            $listing = $this->listingFactory->getListing($row, $row['category_sid']);
            foreach ($this->extraPropertySetters as $extraPropertySetter)
            {
                $extraPropertySetter->setListing($listing);
                $extraPropertySetter->perform();
            }
            self::$listings[$row['sid']] = $listing;
        }
		
		return self::$listings[$row['sid']];
	}
}
