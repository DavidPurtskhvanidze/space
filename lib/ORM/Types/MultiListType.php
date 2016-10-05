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

class MultiListType extends ListType
{
	var $list_values = array();

	function __construct($property_info)
	{
		parent::__construct($property_info);
		if(isset($property_info['list_values']))
		{
			$this->list_values = $property_info['list_values'];
		}
		$this->default_template = 'multilist.tpl';
	}

	function setValue($value)
	{
		if (is_numeric($value))
		{
			$this->property_info['value'] = \App()->Math->decimalToBitsRepresentedAsArray($value);
		}
		elseif (is_array($value))
		{
			$this->property_info['value'] = $value;
		}
	}
	
	function getSQLValue()
	{
		return \App()->Math->bitsRepresentedAsArrayToDecimal($this->property_info['value']);
	}

	function getColumnDefinition()
	{
		return 'BIGINT UNSIGNED NULL DEFAULT \'0\'';
	}

	function getDisplayValue()
	{
		return new MultiListTypeDisplayer($this->property_info);
	}

	public function getExportValue()
	{
		$list_values = $this->property_info['list_values'];
		$value = $this->property_info['value'];
		$checked = array_filter($list_values, function ($v) use ($value)
		{
			return isset($value[$v['rank']]);
		});
		$checked = array_map(function ($v)
		{
			return $v['caption'];
		}, $checked);
		return join(", ", $checked);
	}
}

class MultiListTypeDisplayer
{
	var $property_info = array();

	public function __construct($property_info)
	{
		$this->property_info = $property_info;
	}

	public function __toString()
	{
		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign($this->property_info);
		return $template_processor->fetch('field_types^display/multilist.tpl');
	}
}
