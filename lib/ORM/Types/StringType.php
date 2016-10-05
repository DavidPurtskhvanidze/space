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

class StringType extends Type
{
    private $isUnique;
    private $regExpPattern;

	function __construct($property_info)
	{
		parent::__construct($property_info);
		$this->default_template = 'string.tpl';
		$this->property_info['autocomplete_service_name'] = isset($property_info['autocomplete_service_name']) ? $property_info['autocomplete_service_name'] : null;
		$this->property_info['autocomplete_method_name'] = isset($property_info['autocomplete_method_name']) ? $property_info['autocomplete_method_name'] : null;
        $this->isUnique = (isset($this->property_info['is_unique']) && $this->property_info['is_unique'] === true)? true : false;
        $this->regExpPattern = (isset($this->property_info['pattern'])) ? $this->property_info['pattern'] : false;
	}

	function getPropertyVariablesToAssignTypeSpecific()
	{
		$properties['maxlength'] = $this->property_info['maxlength'];
		$properties['autocomplete_service_name'] = $this->property_info['autocomplete_service_name'];
		$properties['autocomplete_method_name'] = $this->property_info['autocomplete_method_name'];

		return $properties;
	}

    function isValid()
    {
        $this->property_info['value'] = str_replace("\r\n", "\n", $this->property_info['value']);
        if (!empty($this->property_info['maxlength']) && strlen(utf8_decode($this->property_info['value'])) > $this->property_info['maxlength']) {
            $this->addValidationError('DATA_LENGTH_IS_EXCEEDED', array('maxLength' => $this->property_info['maxlength']));
            return false;
        }

        if ($this->isUnique) {
            $isExist = $this->checkIfValueExist();
            if ($isExist) {
                $this->addValidationError('NOT_UNIQUE_VALUE');
                return false;
            }
        }

        if($this->regExpPattern) {
            if(!$this->areSymbolsAllowed($this->regExpPattern)){
                $validationError = (isset($this->property_info['error_txt'])) ? $this->property_info['error_txt'] : 'ERROR_TEXT_NOT_SET';
                $this->addValidationError($validationError);
                return false;
            }
        }

        return true;
    }

    private function checkIfValueExist()
    {
        $table = $this->property_info['table_name'];
        $id = $this->property_info['id'];
        $value = $this->property_info['value'];
        return \App()->DB->query('SELECT * FROM ?w WHERE ?w = ?s AND sid <> ?n', $table, $id, $value, $this->object_sid);
    }

    private function areSymbolsAllowed($pattern)
    {
        return preg_match($pattern, $this->property_info['value']);
    }
	
	static function getFieldExtraDetails() {
		
		return array(
		
			array(
				'id'		=> 'maxlength',
				'caption'	=> 'Maximum Length', 
				'type'		=> 'integer',
				'length'	=> '20',
				'value'		=> '60',
				'minimum'	=> '1',
				'is_required' => true,
				),
		
		);
		
	}

	function getSQLValue()
	{
		if (is_null($this->property_info['value'])) return 'NULL';
		return "'". \App()->DB->real_escape_string($this->property_info['value']) ."'";
	}

    function getKeywordValue()
	{
		return $this->property_info['value'];
	}

	public function getColumnDefinition()
	{ 
		$length = isset($this->property_info['maxlength']) ? $this->property_info['maxlength'] : 64;
		return "VARCHAR($length) CHARACTER SET UTF8"; 
	}
	
	public function getDisplayValue()
	{
		$v = $this->property_info['value'];
        $escape = isset($this->property_info['escape']) ? $this->property_info['escape'] : true;
        if ($escape) {
            $htmlTagConverter = \App()->ObjectMother->createHTMLTagConverterInArray();
            $htmlTagConverter->explore($v);
        }
		return $v;
	}
}
