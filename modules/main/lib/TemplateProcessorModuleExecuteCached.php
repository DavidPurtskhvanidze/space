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

 class TemplateProcessorModuleExecuteCached
{
	private $executer = null;

	public function __construct($executer)
	{
		$this->executer = $executer;
	}

	function execute($params)
	{
		asort($params);
		$lifeTime = $this->convertLifeTime($params['cacheLifeTime']);
		unset($params['cacheLifeTime']);
		$cacheId = $this->getCacheId($params);

		$result = \App()->CacheManager->getData('module_function_content', $cacheId);
		if (!is_null($result))
		{
			return $result;
		}
		else
		{
			$result = $this->executer->execute($params);
		}

		\App()->CacheManager->updateData('module_function_content', $cacheId, $result, $lifeTime);

		return $result;
	}

	private function convertLifeTime($lifeTime)
	{
		$result = (integer) $lifeTime;

		switch(substr($lifeTime, -1, 1))
		{
			case 'Y': $result *= 12;
			case 'M': $result *= 30;
			case 'D': $result *= 24;
			case 'H': $result *= 60;
		}
		return $result;
	}

	private function getCacheId($params)
	{
		$tp = \App()->TemplateProcessor->getFreshInstance();
		$theme = $tp->getTheme();
		return  $theme->getName() . '_' . \App()->I18N->getCurrentLanguage() . '_' . $params['name'] . $params['function'] . md5(serialize($params));
	}
 }
