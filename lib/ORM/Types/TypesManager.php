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


namespace lib\ORM\Types;

class TypesManager
{
	
	private $typesList = array(
		'list' => 'lib\ORM\Types\ListType',
		'multilist' => 'lib\ORM\Types\MultiListType',
		'string' => 'lib\ORM\Types\StringType',
		'text' => 'lib\ORM\Types\TextType',
		'integer' => 'lib\ORM\Types\IntegerType',
		'float' => 'lib\ORM\Types\FloatType',
		'decimal' => 'lib\ORM\Types\DecimalType',
		'money' => 'lib\ORM\Types\MoneyType',
		'file' => 'lib\ORM\Types\UploadFileType',
		'video' => 'lib\ORM\Types\UploadVideoFileType',
		'pictures' => 'lib\ORM\Types\PicturesType',
		'tree' => 'lib\ORM\Types\TreeType',
		'picture' => 'lib\ORM\Types\PictureType',
		'relation' => 'lib\ORM\Types\Type'
	);
	
	function getExtraDetailsByFieldType($field_type)
	{
		if (isset($this->typesList[$field_type]))
		{
			$type = $this->typesList[$field_type];
			return $type::getFieldExtraDetails();
		}

		return array();
	}
}
