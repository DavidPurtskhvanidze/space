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


namespace lib\ORM\Controllers;

class DisplayListController extends ListController
{
	var $list_items			= null;
	
	protected $template_processor;
	function setTemplateProcessor($tp) {$this->template_processor = $tp;}

	function setInputData($input_data)
	{
		parent::setInputData($input_data);
		$this->list_items = $this->ListItemManager->getHashedListItemsByFieldSID($this->field_sid);
	}

	function display($template, $extra_info=array())
	{		
		$this->template_processor->assign("field_sid", $this->field_sid);
		$this->template_processor->assign("list_items", $this->list_items);
		$this->template_processor->assign("field_info", $this->field_info);
		$this->template_processor->assign("type_sid", $this->getTypeSID());
		$this->template_processor->assign("type_info", $this->_getTypeInfo());
		
		foreach($extra_info as $param_name => $param_value) $this->template_processor->assign($param_name, $param_value);
		$this->template_processor->display($template);
	}

	function getTypeSID() {}

	function _getTypeInfo() {}
}
?>
