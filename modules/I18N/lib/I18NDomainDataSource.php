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

class I18NDomainDataSource
{
	var $context;
	var $tr;
	var $tr_admin;
	
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
	
	function getDomainsData()
	{
		$domainIDs = $this->tr_admin->getPageNames();
		$domains = array();
		$domainData = new Data\DomainData();
		foreach ($domainIDs as $domainId)
		{
			$domain = $domainData->create();
			$domain->setID($domainId);
			$domains[] = $domain;
		}
		return $domains;
	}
	
	function getDomainData($domain_id)
	{
		$domainData = new Data\DomainData();
		$domainData = $domainData->create();
		$domainData->setID($domain_id);
		return $domainData;
	}
}

?>
