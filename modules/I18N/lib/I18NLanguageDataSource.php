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

class I18NLanguageDataSource
{
	var $context;
	var $tr;
	var $tr_admin;
	
	function setContext($context)
	{
		$this->context = $context;
	}
	
	function setTranslator($tr)
	{
		$this->tr = $tr;
	}
	
	function setTrAdmin($tr_admin)
	{
		$this->tr_admin = $tr_admin;
	}
	
	function addLanguage($langData)
	{	
		$lang_data = array
		(
			'lang_id'    => $langData->getID(),
			'name'       => $langData->getCaption(),
			'meta'       => $langData->getMeta(),
			'error_text' => $langData->getErrorText(),
			'encoding'   => 'utf-8',
		);
		
		return $this->tr_admin->addLang($lang_data);
	}
	
	function getLanguageData($lang_id)
	{
		$lang_data = $this->tr_admin->getLang($lang_id, 'array');
		$langData = new Data\LangData();
		return $langData->createLangDataFromServer($lang_data);
	}
	
	function getLanguagesData()
	{		
		$langsData = array();
		$langs_data = $this->tr_admin->getLangs('array');
		$langData = new Data\LangData();
		foreach($langs_data as $lang_data)
		{
			$langsData[] = $langData->createLangDataFromServer($lang_data);
		}
		return $langsData;
	}
	
	function updateLanguage($langData)
	{
		$lang_data = array
		(
			'lang_id'    => $langData->getID(),
			'name'       => $langData->getCaption(),
			'meta'       => $langData->getMeta(),
			'error_text' => $langData->getErrorText(),
			'encoding'   => 'utf-8',
		);
		
		return $this->tr_admin->updateLang($lang_data);
	}
	
	function deleteLanguage($lang_id)
	{
		return $this->tr_admin->removeLang($lang_id);
	} 
}

?>
