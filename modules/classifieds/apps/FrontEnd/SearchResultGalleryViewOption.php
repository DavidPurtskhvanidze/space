<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\apps\FrontEnd;

class SearchResultGalleryViewOption implements \modules\classifieds\apps\FrontEnd\ISearchResultViewTypeOption
{
	/**
	 * Search object
	 * @var \lib\ORM\SearchEngine\Search
	 */
	private $search;
	
	public function setSearch(\lib\ORM\SearchEngine\Search $search)
	{
		$this->search = $search;
	}
	
	public static function getOptionId()
	{
		return 'gallery';
	}
	
	public function getSearchResultTemplateName()
	{
		return 'classifieds^gallery_search_results.tpl';
	}
	
	public function getRenderedOption()
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('view_option_id', $this->getOptionId());
		$templateProcessor->assign('search_id', $this->search->getId());
		$templateProcessor->assign('selected_view_type', $this->search->getResultViewType());
		$templateProcessor->assign('search_result_uri', $this->search->getSearchResultsUri());
		
		return $templateProcessor->fetch('classifieds^search_result_gallery_view_option.tpl');
	}

	public static function getOrder()
	{
		return 200;
	}
}
