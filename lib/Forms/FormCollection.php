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


namespace lib\Forms;

class FormCollection
{
	var $forms = array();

	function __construct($object_collection)
	{
		foreach ($object_collection as $object)
		{
			$this->forms[$object->getSID()] = new Form($object);
		}
	}

	function registerTags($template_processor)
	{
		$template_processor->registerPlugin("function", "display", array($this, "tpl_display"));
		$template_processor->registerPlugin("function", "input", array($this, "tpl_input"));
		$template_processor->registerPlugin("function", "search", array($this, "tpl_search"));
	}

	function tpl_display($params, $templateProcessor)
	{
		if (isset($params['object_sid']))
			
			$object_sid = $params['object_sid'];
		
		elseif (isset($params['object_id'])) 
		
			$object_sid = $params['object_id'];

		return $this->forms[$object_sid]->tpl_display($params, $templateProcessor);
	}

    function tpl_input($params, $templateProcessor)
	{
        $object_sid = $params['object_sid'];

		return $this->forms[$object_sid]->tpl_input($params, $templateProcessor);
	}

	function tpl_search($params, $templateProcessor)
	{
        $object_sid = $params['object_sid'];

		return $this->forms[$object_sid]->tpl_search($params, $templateProcessor);
	}

	function makeDisabled($property_id)
	{
		foreach ($this->forms as $form)
		{
			$form->makeDisabled($property_id);
		}
	}

	function makeNotRequired($property_id)
	{
        foreach ($this->forms as $form)
		{
			$form->makeDisabled($property_id);
		}
	}


	function isDataValid()
	{
		$isValid = true;
		foreach ($this->forms as $form)
		{
			$isValid &= $form->isDataValid();
		}
		return $isValid;
	}
}


?>
