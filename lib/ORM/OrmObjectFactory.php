<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\ORM;

class OrmObjectFactory implements \core\IService
{
	public function init(){}

	public function createObjectProperty($property_info)
	{
		$property = new ObjectProperty($property_info);
		$property->setType($this->createType($property_info));
		return $property;
	}

	public function createType($property_info)
	{
		if(!isset($property_info['value'])) $property_info['value'] = null;
		$type = null;
		switch($property_info['type'])
		{
			case 'list': $type = new Types\ListType($property_info);	break;
			case 'multilist': $type = new Types\MultiListType($property_info);	break;
			case 'string': $type = new Types\StringType($property_info);	break;
			case 'text': $type = new Types\TextType($property_info);	break;
			case 'integer': $type = new Types\IntegerType($property_info); break;
			case 'float': $type = new Types\FloatType($property_info); break;
			case 'decimal': $type = new Types\DecimalType($property_info); break;
			case 'money': $type = new Types\MoneyType($property_info); break;
			case 'boolean': $type = new Types\BooleanType($property_info); break;
			case 'geo': $type = new Types\GeoType($property_info); break;
			case 'file': $type = new Types\UploadFileType($property_info); break;
			case 'video': $type = new Types\UploadVideoFileType($property_info); break;
			case 'pictures': $type = new Types\PicturesType($property_info); break;
			case 'tree': $type = new Types\TreeType($property_info); break;
			case 'password': $type = new Types\PasswordType($property_info); break;
			case 'unique_string': $type = new Types\UniqueStringType($property_info); break;
			case 'dashed_unique_string': $type = new Types\DashedUniqueStringType($property_info); break;
			case 'date': $type = new Types\DateType($property_info); break;
			case 'datetime': $type = new Types\DateTimeType($property_info); break;
			case 'picture': $type = new Types\PictureType($property_info); break;
			case 'hidden': $type = new Types\HiddenType($property_info); break;
			case 'relation': $type = new Types\Type($property_info); break;
			case 'rating': $type = new Types\RatingType($property_info);  break;
			case 'calendar': $type = new Types\CalendarType($property_info); break;
			case 'array': $type = new Types\ArrayType($property_info); break;
			case 'object': $type = new Types\ObjectType($property_info); break;
			case 'email': $type = new Types\EmailType($property_info); break;
			case 'unique_email': $type = new Types\UniqueEmailType($property_info); break;
			case 'url': $type = new Types\UrlType($property_info); break;
			case 'transaction_money': $type = new Types\TransactionMoney($property_info); break;
		}
		if(is_null($type)) throw new \Exception("Unknown type \"{$property_info['type']}\" requested for propery \"{$property_info['id']}\"");
		return $type;
	}
}
