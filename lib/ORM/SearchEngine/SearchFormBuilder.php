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


namespace lib\ORM\SearchEngine;


class SearchFormBuilder extends \lib\Forms\Form
{
	private $requestData;

	public function setRequestData($requestData)
	{
		$this->requestData = $requestData;
	}
	
	function registerTags($template_processor)
	{
		$this->template_processor = $template_processor;
		$template_processor->registerPlugin("function", "search", array($this, "tpl_search"));
	}

	function getVariablesToAssign($params)
	{
		return array
		(
			'id' => $params['property'],
			'value' => $this->requestData->get($params['property']),
		);
	}
}
?>
