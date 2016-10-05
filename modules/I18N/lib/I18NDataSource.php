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

class I18NDataSource
{	
	var $context;
	var $languageDataSource;
	var $phraseDataSource;
	var $translationDataSource;
	
	function getInstance()
	{		
		$languageDataSource = new I18NLanguageDataSource();
		$phraseDataSource = new I18NPhraseDataSource();
		$translationDataSource = new I18NTranslationDataSource();
		$domainDataSource = new I18NDomainDataSource();
		
		$i18nDataSource = new I18NDataSource();
		$i18nDataSource->setLanguageDataSource($languageDataSource);
		$i18nDataSource->setPhraseDataSource($phraseDataSource);
		$i18nDataSource->setTranslationDataSource($translationDataSource);
		$i18nDataSource->setDomainDataSource($domainDataSource);
		
		return $i18nDataSource;
	}
	
	function init(&$context, &$fileHelper)
	{
		$this->context = $context;
		
		$adminFactory = new Translation2AdminFactory();
		$adminFactory->setContext($context);
		
		$repo = new I18NAdminRepository();
		$repo->setFileHelper($fileHelper); 
		$repo->setAdminFactory($adminFactory); 
		$repo->load();
		
		$tr_admin = new Translation2AdminWrapper();
		$tr_admin->setContext($context);
		$tr_admin->setRepository($repo);
		
		$phraseDataFactory = new Data\PhraseDataFactory();
		$phraseDataFactory->setLanguageDataSource($this->languageDataSource);
		$phraseDataFactory->setTranslationDataSource($this->translationDataSource);
		
		$this->languageDataSource->setContext($context);
		$this->languageDataSource->setTranslator($tr_admin);
		$this->languageDataSource->setTrAdmin($tr_admin);

		$this->domainDataSource->setContext($context);
		$this->domainDataSource->setTranslator($tr_admin);
		$this->domainDataSource->setTrAdmin($tr_admin);
		
		$this->translationDataSource->setContext($context);
		$this->translationDataSource->setTranslator($tr_admin);
		$this->translationDataSource->setTrAdmin($tr_admin);
		
		$this->phraseDataSource->setContext($context);
		$this->phraseDataSource->setTranslator($tr_admin);
		$this->phraseDataSource->setTrAdmin($tr_admin);		
		$this->phraseDataSource->setPhraseDataFactory($phraseDataFactory);		
	}
	
	function setLanguageDataSource($languageDataSource)
	{
		$this->languageDataSource = $languageDataSource;
	}
	
	function setPhraseDataSource($phraseDataSource)
	{
		$this->phraseDataSource = $phraseDataSource;
	}
	
	function setTranslationDataSource($translationDataSource)
	{
		$this->translationDataSource = $translationDataSource;
	}
	
	function setDomainDataSource($dataSource)
	{
		$this->domainDataSource = $dataSource;
	}	
	
	function gettext($domain_id, $phrase_id, $lang)
	{
		return $this->translationDataSource->gettext($phrase_id, $domain_id, $lang);
	}
	
	function addLanguage(&$langData)
	{				
		return $this->languageDataSource->addLanguage($langData);
	}
	
	function getLanguageData($lang_id)
	{		
		$data = $this->languageDataSource->getLanguageData($lang_id);
		return $data;
	}
	
	function getLanguagesData()
	{
		$languagesData = $this->languageDataSource->getLanguagesData();
		return $languagesData;
	}
	
	function updateLanguage($langData)
	{
		return $this->languageDataSource->updateLanguage($langData);
	}
	
	function deleteLanguage($lang_id)
	{
		return $this->languageDataSource->deleteLanguage($lang_id);
	} 	
	
	function getPhraseData($phrase_id, $domain_id)
	{
		$phraseData = $this->phraseDataSource->getPhraseData($phrase_id, $domain_id);
		return $phraseData;
	}
	
	function addPhrase($phraseData)
	{			
		return $this->phraseDataSource->addPhrase($phraseData);
	}
	
	function updatePhrase($phraseData)
	{				
		return $this->phraseDataSource->updatePhrase($phraseData);
	}
	
	function deletePhrase($phrase_id, $domain_id)
	{			
		return $this->phraseDataSource->deletePhrase($phrase_id, $domain_id);
	}
	
	function getDomainPhrases($domainId)
	{
		$data = $this->phraseDataSource->getDomainPhrases($domainId);
		return $data;
	}
	
	function getDomainsData()
	{
		$data = $this->domainDataSource->getDomainsData();
		return $data;
	}
	
	function getDomainData($domainId)
	{
		$data = $this->domainDataSource->getDomainData($domainId);
		return $data;
	}
}


?>
