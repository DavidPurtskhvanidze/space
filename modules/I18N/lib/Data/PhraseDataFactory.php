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


namespace modules\I18N\lib\Data;

class PhraseDataFactory
{
	function setLanguageDataSource(&$languageDataSource)
	{
		$this->languageDataSource = $languageDataSource;
	}
	
	function setTranslationDataSource(&$translationDataSource)
	{
		$this->translationDataSource = $translationDataSource;
	}
	
	function create($phrase_id, $domain_id)
	{

		$phraseData = new PhraseData();
		
		$translations = $this->getPhraseAllTranslations($phrase_id, $domain_id);
		
		$phraseData->setID($phrase_id);
		$phraseData->setDomainID($domain_id);
		$phraseData->setTranslations($translations);
		
		return $phraseData;
	}
	
	function &getPhraseAllTranslations($phrase_id, $domain_id)
	{
		$langsData = $this->languageDataSource->getLanguagesData();
		foreach (array_keys($langsData) as $i)
		{
			$langData = $langsData[$i];
			$lang_id = $langData->getID();
			$translationsData[] = $this->translationDataSource->getTranslation($phrase_id, $domain_id, $lang_id);
		}
		return $translationsData;
	}
}

?>
