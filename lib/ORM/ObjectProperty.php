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

class ObjectProperty
{
	var $value;
	/**
	 * @var \lib\ORM\Types\Type
	 */
	var $type;
	var $order;

	var $sid;
	var $id;
	var $caption;

	var $is_system;
	var $is_required;
	var $save_into_db;

	var $object_sid;
	var $columnName;
	var $tableName;
	var $tableAlias;
	var $joinCondition;

	protected $object;

	/**
	 * @var ObjectPropertyValueValidator[]
	 */
	private $validators = array();

	public function getColumnName()
	{
		return $this->columnName;
	}
	public function getTableName()
	{
		return $this->tableName;
	}
	public function getTableAlias()
	{
		return isset($this->tableAlias) ? $this->tableAlias : $this->tableName;
	}
	public function getOrderClause()
	{
		return $this->type->getOrderClause($this->getFullColumnName());
	}
	public function getFullColumnName()
	{
		return "`{$this->getTableAlias()}`.`{$this->getColumnName()}`";
	}
	public function getJoinCondition()
	{
		return $this->joinCondition;
	}

	function __construct($property_info)
	{
		$this->id = $property_info['id'];
		$this->caption = $property_info['caption'];

		if (isset($property_info['value']) && is_string($property_info['value']))	$property_info['value'] = trim($property_info['value']);

		$this->sid 			= isset($property_info['sid']) 	   	   ? $property_info['sid'] 			: null;
		$this->value 		= isset($property_info['value']) 	   ? $property_info['value'] 		: null;
		$this->is_system 	= isset($property_info['is_system'])   ? $property_info['is_system'] 	: true;
		$this->is_required 	= isset($property_info['is_required']) ? $property_info['is_required'] 	: false;
		$this->save_into_db = isset($property_info['save_into_db'])? $property_info['save_into_db']	: true;
		$this->order 		= isset($property_info['order'])	   ? $property_info['order']		: null;
		$this->columnName	= isset($property_info['column_name']) ? $property_info['column_name'] : $this->id;
		$this->tableName	= isset($property_info['table_name']) ? $property_info['table_name'] : null;
		$this->tableAlias	= isset($property_info['table_alias']) ? $property_info['table_alias'] : $this->tableName;
		$this->joinCondition = isset($property_info['join_condition']) ? $property_info['join_condition'] : null;
		$this->validators = isset($property_info['validators']) ? $property_info['validators'] : array();
	}

	public function setType($type){ $this->type = $type; }

	function getPropertyVariablesToAssign()
	{
		return $this->type->getPropertyVariablesToAssign();
	}

    function getSavableValue() { return $this->type->getSavableValue(); }

	function setObjectSID($sid)
	{
		$this->type->setObjectSID($sid);
		$this->object_sid = $sid;
	}

	function setObject($object)
	{
		$this->type->setObject($object);
		$this->object = $object;
	}

	function isValid($type_sid = null)
	{
		if ($this->type->isEmpty())
		{
			if ($this->is_required)
			{
				$this->type->addValidationError('EMPTY_VALUE');
				return false;
			}
			else
			{
				return true;
			}
		}

		if (!$this->type->isValid($type_sid))
		{
			return false;
		}

		// additional property validators
		foreach ($this->validators as $validator)
		{
			if (!$validator->isValid($this->value, $this->id, $this->object))
			{
				$this->type->addValidationError($validator->getErrorCode(), $validator->getExtraParameters(), $validator->getErrorTemplateModule());
				return false;
			}
		}

		return true;
	}

	public function getValidationErrorMessage()
	{
		return $this->type->getValidationErrorMessage();
	}

	function isSearchValueValid()
	{
		if ($this->type->isEmpty())
		{
			return false;
		}
		else
		{
			$isValid = $this->type->isValid();
			\App()->ErrorMessages->fetchMessages(); // when isValid() method is called, messages are written to Session. In search form, messages should be left there
			return $isValid;
		}
	}

    function getID() 		{ return $this->id; }
    function getSID() 		{ return $this->sid; }
	function getCaption() 	{ return $this->caption; }
	function isRequired()	{ return $this->is_required; }
	function isSystem() 	{ return $this->is_system; }
	function saveIntoBD() 	{ return $this->save_into_db && $this->type->isSaveIntoDB(); }
	function getOrder() 	{ return $this->order; }

	function setValue($value)
	{
		$this->value = $value;
		$this->type->setValue($value);
	}

	function getValue() 			{ return $this->type->getValue(); }
	function getSQLValue() 			{ return $this->type->getSQLValue(); }
	function getKeywordValue() 		{ return $this->type->getKeywordValue(); }
	function getDisplayValue() 		{ return $this->type->getDisplayValue(); }
	function getExportValue() 		{ return $this->type->getExportValue(); }

	function getType()				{ return $this->type->getType(); }
	function getSQLType()			{ return $this->type->getSQLType(); }

	function getDefaultTemplate() 	{ return $this->type->getDefaultTemplate(); }

	function makeRequired() 		{ $this->is_required = true;  $this->type->makeRequired(); }
	function makeNotRequired() 		{ $this->is_required = false; $this->type->makeNotRequired(); }

	function setSaveFlag() 			{ $this->save_into_db = true; }
	function setDontSaveFlag() 		{ $this->save_into_db = false; }

    public function __clone()
    {
    	$this->type = clone $this->type;
    }

	public function getColumnDefinition()
	{
		$colDef = $this->type->getColumnDefinition();
		if (is_null($colDef)) return null;
        return !is_array($colDef)
            ? '`' . $this->id . '` ' . $colDef
            : '(' . implode(', ', $colDef) . ')';
    }

	public function isEmpty()
	{
		return $this->type->isEmpty();
	}

	public function defineRefineSearchExtraDetailsAttributes()
	{
		$this->type->defineRefineSearchExtraDetailsAttributes();
	}

	public function getValueForEncodingToJson()
	{
		return $this->type->getValueForEncodingToJson();
	}
    public function __call($name, $args)
    {
        return call_user_func_array([$this->type, $name], $args);
    }
}
