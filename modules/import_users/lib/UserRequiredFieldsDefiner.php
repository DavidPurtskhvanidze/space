<?php
/**
 *
 *    Module: import_users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_users-7.5.0-1
 *    Tag: tags/7.5.0-1@19788, 2016-06-17 13:19:38
 *
 *    This file is part of the 'import_users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_users\lib;

class UserRequiredFieldsDefiner
{
	var $requiredFieldsIds = array('username');
	
	function process(&$user)
	{
		$properties = $user->getProperties();
		array_walk($properties, array($this, 'processProperty'));
	}
	
	function processProperty(&$property)
	{
		$propertyId = $property->getID();
		if (in_array($propertyId, $this->requiredFieldsIds))
		{
			$property->makeRequired();
		}
		else
		{
			$property->makeNotRequired();
		}
	}
}
