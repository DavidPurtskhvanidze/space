<?php
/**
 *
 *    Module: site_pages v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: site_pages-7.5.0-1
 *    Tag: tags/7.5.0-1@19834, 2016-06-17 13:21:53
 *
 *    This file is part of the 'site_pages' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\site_pages\lib;

class SitePage
{
	var $pageInfo 	= array (
            'id' => null,
			'uri' => null,
			'no_index' => 0,
			'module' => null,
			'function' =>  null,
			'template' => null,
			'title' =>  null,
			'application_id' => null,
			'parameters' =>  array(),
			'keywords' =>  null,
			'description' => '',
			'pass_parameters_via_uri' => null,
		);

	function getPageInfo()
	{
		return $this->pageInfo;
	}

	public function mergeDataWithRequest($request)
	{
		$this->incorporateData(
			$request,
			isset($request['additionalParameterNames']) ? $request['additionalParameterNames'] : array(),
			isset($request['additionalParameterValues']) ? $request['additionalParameterValues'] : array()
		);
	}

	function incorporateData($data, $additionalParamNames = array(), $additionalParamValues = array())
	{
		foreach ($this->pageInfo as $key => $dummy)
		{
			if (isset($data[$key]))
			{
				$this->pageInfo[$key] = $data[$key];
			}
		}

		$extraFields = \App()->PageManager->getExtraFields($this->pageInfo['application_id']);
		foreach ($extraFields as $extraField)
		{
			$extraFieldId = $extraField->getId();
			$this->pageInfo[$extraFieldId] = isset($data[$extraFieldId]) ? $data[$extraFieldId] : null;
		}

		foreach ($additionalParamNames as $key => $paramName)
		{
			if (!in_array($paramName, $this->pageInfo['parameters']))
			{
				$this->pageInfo['parameters'][$paramName] = isset($additionalParamValues[$key]) ? $additionalParamValues[$key] : null;
			}
		}
	}

	function isDataValid()
	{
		$isValid = true;
		if (!\App()->doesAppExist($this->pageInfo['application_id']))
		{
			\App()->ErrorMessages->addMessage('UNKNOWN_APPLICATION');
			$isValid = false;
		}
        if (empty($this->pageInfo['id']))
		{
			\App()->ErrorMessages->addMessage('ID_NOT_SPECIFIED');
			$isValid = false;
		}
		if (empty($this->pageInfo['uri']))
		{
			\App()->ErrorMessages->addMessage('URI_NOT_SPECIFIED');
			$isValid = false;
		}
		if (empty($this->pageInfo['module']))
		{
			\App()->ErrorMessages->addMessage('MODULE_NOT_SPECIFIED');
			$isValid = false;
		}
		elseif (!in_array($this->pageInfo['module'], \App()->ModuleManager->getModulesFunctionsConfigForApp($this->pageInfo['application_id'])->getModulesList()))
		{
			\App()->ErrorMessages->addMessage('NON_EXISTENT_MODULE_SPECIFIED');
			$isValid = false;
		}
		if (empty($this->pageInfo['function']))
		{
			\App()->ErrorMessages->addMessage('FUNCTION_NOT_SPECIFIED');
			$isValid = false;
		}
		elseif (!in_array($this->pageInfo['function'], \App()->ModuleManager->getModulesFunctionsConfigForApp($this->pageInfo['application_id'])->getFunctionsList($this->pageInfo['module'])))
		{
			\App()->ErrorMessages->addMessage('NON_EXISTENT_FUNCTION_SPECIFIED');
			$isValid = false;
		}
		return $isValid;
	}
}
