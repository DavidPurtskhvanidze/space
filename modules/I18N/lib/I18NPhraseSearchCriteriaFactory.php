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

class I18NPhraseSearchCriteriaFactory
{
	function create($criteria)
	{
				$phrase_id = isset($criteria['phrase_id']) ? $criteria['phrase_id'] : null;
		$domain_id = isset($criteria['domain']) ? $criteria['domain'] : null;
		$phraseSearchCriteria =  new I18NPhraseSearchCriteria();
		$phraseSearchCriteria->setPhraseID($phrase_id);
		$phraseSearchCriteria->setDomainID($domain_id);
		return $phraseSearchCriteria;
	}
}

?>
