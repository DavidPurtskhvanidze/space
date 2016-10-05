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

class I18NAdmin
{	
	var $data_source;
	
	function setDataSource(&$data_source) 
	{
		$this->data_source =$data_source;
	}
	
	function addLanguage(&$langData) 
	{		
		return $this->data_source->addLanguage($langData);		
	}
	
	function getLanguageData($lang_id)
	{
		$data = $this->data_source->getLanguageData($lang_id);
		return $data;
	}
	
	function getLanguagesData()
	{
		$languagesData = $this->data_source->getLanguagesData();
		return $languagesData;
	}
	
	function updateLanguage(&$langData) 
	{				
		return $this->data_source->updateLanguage($langData);		
	}
	
	function deleteLanguage($lang_id) 
	{
		return $this->data_source->deleteLanguage($lang_id);
	}
	
	function addPhrase(&$phraseData) 
	{
		return $this->data_source->addPhrase($phraseData);	
	}
		
	function getPhraseData($phrase_id, $domain_id)
	{
		$phraseData = $this->data_source->getPhraseData($phrase_id, $domain_id);
		return $phraseData;
	}	
	
	function updatePhrase(&$phraseData) 
	{				
		return $this->data_source->updatePhrase($phraseData);		
	}
	
	function deletePhrase($phrase_id, $domain_id) 
	{
		return $this->data_source->deletePhrase($phrase_id, $domain_id);
	}
	
	function getDomainPhrases($domainId)
	{
		$data = $this->data_source->getDomainPhrases($domainId);
		return $data;
	}
	
	function getDomainsData()
	{
		$data = $this->data_source->getDomainsData();
		return $data;
	}
}

?>
