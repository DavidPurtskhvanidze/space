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

class I18NPhraseSearchCriteria
{
	var $phrase_id;
	var $domain_id;
	
	function getPhraseID()
	{
		return $this->phrase_id;
	}
	
	function getDomainID()
	{
		return $this->domain_id;
	}
	
	function setPhraseID($phrase_id)
	{
		$this->phrase_id = $phrase_id;
	}
	
	function setDomainID($domain_id)
	{
		$this->domain_id = $domain_id;
	}
}

?>
