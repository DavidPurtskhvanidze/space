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

class UniqueStringType extends StringType {

	private $allowedSymbolsPattern = '/(^\w+$)/';

	public function __construct($property_info)
	{
		parent::__construct($property_info);
		if (!empty($property_info['allowed_symbols_pattern']))
			$this->allowedSymbolsPattern = $property_info['allowed_symbols_pattern'];
	}


	function isValid($category_sid = null, $pattern = null)
	{

		if (!parent::isValid()) return false;

		if (is_null($pattern)) $pattern = $this->allowedSymbolsPattern;

		if(!$this->areSymbolsAllowed($pattern))
		{
			$this->addValidationError('NOT_VALID_ID_VALUE');
			return false;
		}
		$table = $this->property_info['table_name'];
        $count = \App()->DB->getSingleValue("SELECT count(*) FROM " . $table . " WHERE ?w = ?s AND sid <> ?n",
		        $this->property_info['id'], $this->property_info['value'], $this->object_sid);

		if ($count)
		{
			$this->addValidationError('NOT_UNIQUE_VALUE');
			return false;
		}
		return true;
	}
	
	function areSymbolsAllowed($pattern) {
		return preg_match($pattern, $this->property_info['value']);
	}

	public function getColumnDefinition(){ return 'VARCHAR('. $this->property_info['maxlength'] .') CHARACTER SET UTF8'; }
	
}
