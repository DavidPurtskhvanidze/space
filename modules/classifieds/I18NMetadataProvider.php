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


namespace modules\classifieds;

class I18NMetadataProvider implements \modules\I18N\IMetadataProvider
{
	public function getVarName()
	{
		return 'listing';
	}

	public function getMetadata($propertyName, $varValue)
	{
		$typeName = $varValue['type']['id'];
		$typeSid = \App()->CategoryManager->getCategorySIDByID($typeName);
		if($propertyName == 'views')
		{
			$res = array('type' => 'integer', 'property' => array
				(
					'id'		=> 'views',
					'type'		=> 'string',
					'is_system' => true,
					'caption'	=> 'Views',
					'value'		=> $varValue[$propertyName],
				)
			);
			return $res;
		}
		$property = \App()->ListingManager->getPropertyByPropertyName($propertyName, $typeSid);
		if(is_null($property))
		{
			return null;
		}

		$propertyType = $property->getType();
		if(in_array($propertyType, array('date', 'int', 'integer')))
		{
			$res = array('type' => $propertyType, 'property' => &$property->type->property_info);
			return $res;
		}
		if(in_array($propertyType, array('float', 'decimal', 'money')))
		{
			$res = array('type' => 'float', 'signs_num' => $property->type->property_info['signs_num']);
			return $res;
		}
		if(in_array($propertyType, array('list', 'multilist', 'tree')))
		{
			return array('domain' => 'Property_' . $propertyName);
		}
	}
}
