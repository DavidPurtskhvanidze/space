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

class TransactionMoney extends Type
{
	function __construct($propertyInfo)
	{
		parent::__construct($propertyInfo);
		$this->default_template = 'float.tpl';
	}

	function setValue($value)
	{
		$this->property_info['value'] = trim($value);
	}
	
	public function isValid()
	{
		return \App()->PaymentSystemManager->getCurrentPaymentMethod()->isPricePropertyValueValid($this);
	}
	
	public function getSQLValue()
	{
		$this->getValue();
		if ($this->getValue() == '')
			return null;
		return $this->getValue();
	}
	
	public function getKeywordValue()
	{
		return $this->getSQLValue();
	}
	
	public function getColumnDefinition()
	{
		return "DECIMAL(30, 2)";
	}
	
	public function getValue()
	{
		return \App()->PaymentSystemManager->getCurrentPaymentMethod()->formatPrice(parent::getValue());
	}
	
	function getPropertyVariablesToAssignTypeSpecific()
	{
		return array(
			'id' 	=> $this->property_info['id'],
			'value'	=> $this->getValue(),
		);
	}
}
