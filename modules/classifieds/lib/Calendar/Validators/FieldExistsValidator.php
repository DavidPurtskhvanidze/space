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


namespace modules\classifieds\lib\Calendar\Validators;

class FieldExistsValidator
{
	var $reflector;
	private $DB;

	public function setDB($DB)
	{
		$this->DB = $DB;
	}

	public function setReflector($reflector)
	{
		$this->reflector = $reflector;
	}

	public function isValid($value)
	{
		$listing_sid = $this->reflector->get('listing_sid');
		$categoryFieldSids = array_map(function ($fieldInfo)
		{
			return $fieldInfo['sid'];
		}, \App()->ListingFieldManager->getListingFieldsInfoByCategory(\App()->ListingManager->getCategorySidByListingSid($listing_sid)));
		return in_array($value, $categoryFieldSids);
	}
}
