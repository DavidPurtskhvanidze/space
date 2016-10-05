<?php
/**
 *
 *    Module: I18N v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: I18N-7.5.0-1
 *    Tag: tags/7.5.0-1@19784, 2016-06-17 13:19:28
 *
 *    This file is part of the 'I18N' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\I18N\lib;

class I18NSwitchLanguageAgent
{
	var $isNeedSwitchLang = true;
	
	function setSession(&$session)
	{
		$this->session = $session;
	}
	
	function setContext(&$context)
	{
		$this->context = $context;
	}
	
	function setRequestData(&$requestData)
	{
		$this->requestData = $requestData;
	}
	
	function setI18N(&$i18n)
	{
		$this->i18n = $i18n;
	}
	
	function isNeedSwitchLang()
	{
		return $this->isNeedSwitchLang;
	}
	
	function execute()
	{
		$this->isNeedSwitchLang = false;
		$existLanguage = $this->fetchExistLanguage();
		if($existLanguage !== $this->context->getLang())
		{
			$this->context->setLang($existLanguage);
		}
	}
	
	function fetchExistLanguage()
	{
		$lang_priority = array
		(
			$this->requestData->getValue('lang'),
			$this->context->getLang(),
			$this->context->getDefaultLang()
		);
		foreach ($lang_priority as $lang)
		{
			if($this->i18n->languageExists($lang) && $this->i18n->isLanguageActive($lang))
			{
				return $lang;
			}
		}
	}
}

?>
