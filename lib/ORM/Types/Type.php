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

// interface

class Type
{
	var $property_info		= null;
	var $object_sid 		= null;

	var $default_template	= null;
	protected $object = null;
	protected $validationError = array();

    function __construct($property_info)
    {
        $this->property_info = $property_info;
        $this->sql_type = 'CHAR';
	}

	final public function getPropertyVariablesToAssign()
	{
		$base = array
		(
			'sid' => $this->property_info['sid'],
			'id' => $this->property_info['id'],
			'caption' => $this->property_info['caption'],
			'type' => $this->property_info['type'],
			'is_required' => $this->property_info['is_required'],
			'value' => $this->property_info['value'],
			'hasError' => !empty($this->validationError),
			'error' => $this->getValidationErrorMessage(),
		);
		return array_merge($base, $this->getPropertyVariablesToAssignTypeSpecific());

	}

    public function getColumnsList()
    {
        return [];
    }

    public function hasMultipleColumns(){
        return false;
    }

	/**
	 * @return string
	 */
	public function getValidationErrorMessage()
	{
        if (empty($this->validationError)) return null;

        $templateProcessor = \App()->getTemplateProcessor();
        $templateProcessor->assign($this->validationError['data']);
        $moduleForTemplate = !empty($this->validationError['moduleForTemplate']) ? $this->validationError['moduleForTemplate'] : 'miscellaneous';
        $errorTemplateFileName = $moduleForTemplate . "^error_messages/" . strtolower($this->validationError['code']) . ".tpl";
        $errorMessage = $templateProcessor->fetch($errorTemplateFileName);
        return $errorMessage;
	}


	function getPropertyVariablesToAssignTypeSpecific()
	{
		return array();
	}

	function setObjectSID($sid)
	{
		$this->object_sid = $sid;
	}

	function setObject(&$object)
	{
		$this->object = $object;
	}

	function getSavableValue()
	{
		return $this->property_info['value'];
	}

	function isValid()
	{
		return true;
	}

	function setValue($value)
	{
		$this->property_info['value'] = $value;
	}

	function getValue()
	{
		return $this->property_info['value'];
	}

	function getDisplayValue()
	{
		return $this->getValue();
	}

	function getSQLValue()
	{
		if (is_null($this->property_info['value'])) return 'NULL';
		$value = \App()->DB->real_escape_string($this->property_info['value']);
		return "'$value'";
	}

    function getSQLValues()
	{
        return [];
	}

	function getKeywordValue()
	{
		return "";
	}

	function getType()
	{
		return $this->property_info['type'];
	}

	function getSQLType()
	{
		return $this->sql_type;
	}

	static function getFieldExtraDetails()
	{
		return array();
	}

	function getDefaultTemplate()
	{
		return $this->default_template;
	}

    function makeRequired() 	{ $this->property_info['is_required'] = true; }
    function makeNotRequired() 	{ $this->property_info['is_required'] = false; }
    
	function isEmpty() {
		
		$value_is_empty = false;

        if (is_array($this->property_info['value'])) {

            if (empty($this->property_info['value'])) $value_is_empty = true;
	    	
	        foreach ($this->property_info['value'] as $field_value) {
	        	
	        	$field_value = trim($field_value);

	            if ($field_value == '') {
	            	
	                $value_is_empty = true;
	                
	                break;
	            }
	        }
	        
	    } else if (!is_object($this->property_info['value'])) {
	    	$this->property_info['value'] = trim($this->property_info['value']);
	
	        $value_is_empty = ($this->property_info['value'] == '');
	    }
	    
	    return $value_is_empty;
		
	}
	
	public function getColumnDefinition(){ return 'INT';}	
	
	public function isSaveIntoDB() {return true;}

	public function getOrderClause($fullColumnName)
	{
		return $fullColumnName;
	}

	public function defineRefineSearchExtraDetailsAttributes()
	{
	}

	public function addValidationError($errorCode, $errorData = array(), $moduleForTemplate = null)
	{
		$baseData = array
		(
			'fieldCaption' => $this->property_info['caption']
		);
		$errorData = array_merge($baseData, $errorData);
		$this->validationError = array
		(
			'code' => $errorCode,
			'data' => $errorData,
			'moduleForTemplate' => $moduleForTemplate,
		);
	}

	public function getValueForEncodingToJson()
	{
		if (is_object($this->getDisplayValue()))
		{
			return (string) $this->getDisplayValue();
		}
		return $this->getDisplayValue();
	}

	public function getExportValue()
	{
		return $this->getDisplayValue();
	}
}
