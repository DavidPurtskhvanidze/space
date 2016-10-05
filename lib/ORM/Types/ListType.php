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


class ListType extends Type
{
	var $list_values = array();

	function __construct($property_info)
	{
		parent::__construct($property_info);
		if(isset($property_info['list_values']))
		{
			$this->list_values = $property_info['list_values'];
		}
		$this->default_template = 'list.tpl';
	}

	function getPropertyVariablesToAssignTypeSpecific()
	{
		return array
		(
			'list_values' => $this->list_values
		);
	}

	function getSQLValue()
	{
		if (empty($this->property_info['value']) && !is_numeric($this->property_info['value'])) return null;
		return "'". \App()->DB->real_escape_string($this->property_info['value']) ."'";
	}

    function getKeywordValue()
	{
		return $this->getDisplayValue();
	}

	function getDisplayValue()
	{
        $tp = \App()->getTemplateProcessor();
        $tp->setIfAddTemplateStartEndComments(false);
        $tp->assign($this->property_info);
        return $tp->fetch('field_types^display/list.tpl');
	}

	public function getOrderClause($fullColumnName)
	{
		$orderedListValues = $this->list_values;
		usort($orderedListValues, function ($value1, $value2) {
			return strcasecmp($value1['caption'], $value2['caption']);
		});
		$orderedListValueSids = array_map(function ($listValue)
		{
			return "'". \App()->DB->real_escape_string($listValue['id']) ."'";
		}, $orderedListValues);
		$orderedListValueSidsString = join(", ", $orderedListValueSids);
		return "FIELD({$fullColumnName}, {$orderedListValueSidsString})";
	}
}
