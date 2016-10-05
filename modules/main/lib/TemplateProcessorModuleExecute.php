<?php
/**
 *
 *    Module: main v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: main-7.5.0-1
 *    Tag: tags/7.5.0-1@19796, 2016-06-17 13:19:59
 *
 *    This file is part of the 'main' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\main\lib;

 class TemplateProcessorModuleExecute
{
	public function execute($params)
	{
		$name = isset($params['name']) ? $params['name'] : '';
		$function = isset($params['function']) ? $params['function'] : '';
		unset($params['name']);
		unset($params['function']);

		if (empty($name) || empty($function))
		{
			return '<!-- Either module or function is not specified in call to {module ..} -->';
		}
		$this->parseParameters($params);
		$inheritRequest = isset($params['inheritRequest']) ? $params['inheritRequest'] : true;
		$isInternalFunctionCall = true;
		$result = \App()->ModuleManager->executeFunction($name, $function, $params, $inheritRequest, $isInternalFunctionCall);

		return $result;
	}

	 private function parseParameters(&$params)
	 {
		 if (isset($params['QUERY_STRING']))
		 {
			 $parameters = array();
			 parse_str($params['QUERY_STRING'], $parameters);
			 $params = array_merge($parameters, $params);
			 unset($params['QUERY_STRING']);
		 }
	 }
} 
