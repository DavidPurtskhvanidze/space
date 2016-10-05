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


namespace modules\I18N\lib\Actions;

class SearchPhraseAction
{
	function __construct($i18n, $criteria, $template_processor)
	{
		$this->i18n = $i18n;
		$this->criteria =$criteria;
		$this->template_processor = $template_processor;
	}
	
	function canPerform()
	{
		return true;
	}
	
	function perform()
	{
		$phraseSearchCriteriaFactory = $this->i18n->getPhraseSearchCriteriaFactory();
		$phraseSearchCriteria = $phraseSearchCriteriaFactory->create($this->criteria);
		
		$phrases = $this->i18n->searchPhrases($phraseSearchCriteria);
		
		$this->template_processor->assign('phrases', $phrases);
		$this->template_processor->assign('criteria', $this->criteria);
	}
}
