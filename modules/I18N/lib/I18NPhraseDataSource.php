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

class I18NPhraseDataSource
{
	var $context;
	var $tr;
	var $tr_admin;
	var $phraseDataFactory;
	
	function setContext(&$context)
	{
		$this->context = $context;
	}
	
	function setTranslator(&$tr)
	{
		$this->tr = $tr;
	}
	
	function setTrAdmin(&$tr_admin)
	{
		$this->tr_admin = $tr_admin;
	}
	
	function setPhraseDataFactory(&$phraseDataFactory)
	{
		$this->phraseDataFactory = $phraseDataFactory;
	}
	
	function &getPhraseData($phrase_id, $domain_id)
	{						
		$phraseData = $this->phraseDataFactory->create($phrase_id, $domain_id);
		
		return $phraseData;
	}
	
	function addPhrase(&$phraseData)
	{		
		$phrase_data = $this->_get_savable_phrase_data_structure($phraseData);
		
		return $this->tr_admin->add($phrase_data['id'], $phrase_data['domain'], $phrase_data['translations']);
	}
	
	function updatePhrase(&$phraseData)
	{		
		$phrase_data = $this->_get_savable_phrase_data_structure($phraseData);
		
		return $this->tr_admin->update($phrase_data['id'], $phrase_data['domain'], $phrase_data['translations']);
	}
	
	function _get_savable_phrase_data_structure(&$phraseData)
	{		
		$translations = array();
		$translationsData = $phraseData->getTranslations();
		
		foreach ($translationsData as $key => $value)
		{
			$translationData = $translationsData[$key];
			
			$translations[$translationData->getLanguageID()] = $translationData->getTranslation();
		}
		
		return array
		(
			'id' 			=> $phraseData->getID(),
			'domain' 		=> $phraseData->getDomainID(),
			'translations' 	=> $translations,
		);
	}
	
	function deletePhrase($phrase_id, $domain_id)
	{			
		return $this->tr_admin->remove($phrase_id, $domain_id);
	}
	
	function &getDomainPhrases($domainId)
	{
		$page = $this->tr->getRawPage($domainId, $this->context->getDefaultLang());
		$phrases = array();
		foreach (array_keys($page) as $phraseId)
		{
			$phrases[] = $this->getPhraseData($phraseId, $domainId);
		}
		return $phrases;
	}
}

?>
