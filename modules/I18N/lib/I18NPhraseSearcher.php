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

class I18NPhraseSearcher
{
	var $dataSource;
	var $matcher;
	
	function setDataSource(&$dataSource)
	{
		$this->dataSource = $dataSource;
	}

	function setMatcher(&$matcher)
	{
		$this->matcher = $matcher;
	}
	
	function &search(&$criteria)
	{
		$domainsData = $this->getDomainsData($criteria->getDomainID());
		$phrasesData = $this->getAllPhrases($domainsData);
		
		$query = $criteria->getPhraseID();
		if(!empty($query))
		{
			$phrasesData = $this->filterPhrases($query, $phrasesData);
		}
		return $phrasesData;
	}
	
	function &getDomainsData($domain_id)
	{
		if (empty($domain_id))
		{
			$domainsData = $this->dataSource->getDomainsData();
		}
		else
		{
			$domainData = $this->dataSource->getDomainData($domain_id);
			$domainsData = array(&$domainData);
		}
		return $domainsData;
	}

	function &getAllPhrases(&$domainsData)
	{	
		$phrasesData = array();
		foreach (array_keys($domainsData) as $i)
		{
			$domainData = $domainsData[$i];
			$domainPhrases = $this->dataSource->getDomainPhrases($domainData->getID());
			$phrasesData = array_merge($phrasesData, $domainPhrases);
		}
		return $phrasesData;
	}
	
	function &filterPhrases($query, &$phrasesData)
	{
		$this->matcher->setQuery($query);
		
		$filteredPhrasesData = array();
		foreach (array_keys($phrasesData) as $i)
		{
			if ($this->matcher->match($phrasesData[$i]->getID()))
			{
				$filteredPhrasesData[] = $phrasesData[$i];
			}
		}
		return $filteredPhrasesData;
	}
}

?>
