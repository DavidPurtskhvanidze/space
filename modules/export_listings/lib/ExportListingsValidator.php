<?php
/**
 *
 *    Module: export_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19779, 2016-06-17 13:19:16
 *
 *    This file is part of the 'export_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_listings\lib;

class ExportListingsValidator
{
	private $requestData;
	private $search;
	private $errors = array();

	public function setRequestData($requestData)
	{
		$this->requestData = $requestData;
	}
	public function setSearch($search)
	{
		$this->search = $search;
	}
	
	function isValid()
	{
		$exportProperties = $this->requestData->get('export_properties');
		if (empty($exportProperties))
		{
			$this->errors[] = 'EMPTY_EXPORT_PROPERTIES';
			\App()->ErrorMessages->addMessage('EMPTY_EXPORT_PROPERTIES');
		}
		if ($this->search->getNumberOfObjectsFound() == 0)
		{
			$this->errors[] = 'EMPTY_EXPORT_DATA';
			\App()->ErrorMessages->addMessage('EMPTY_EXPORT_DATA');
		}
		return empty($this->errors);
	}
	
	function getErrors()
	{
		return $this->errors;
	}
}
