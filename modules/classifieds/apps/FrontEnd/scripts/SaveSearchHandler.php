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


namespace modules\classifieds\apps\FrontEnd\scripts;

class SaveSearchHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Save Search';
	protected $moduleName = 'classifieds';
	protected $functionName = 'save_search';
	protected $rawOutput = true;

	private $templateProcessor;
	
	public function respond()
	{
		$this->templateProcessor = \App()->getTemplateProcessor();
		
		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'save' && $this->isSearchDataExist())
		{
			$htmlTagConverter =  \App()->ObjectMother->createHTMLTagConverter();
			$searchName = isset($_REQUEST['search_name']) ? trim($htmlTagConverter->getConverted($_REQUEST['search_name'])) : null;
			if (empty($searchName))
			{
				$this->displayErrors(array('EMPTY_SEARCH_NAME'));
				$this->displayForm();
			}
			else
			{
				$search = $this->getCurrentSearch();
				$errors = array();
				$storage = \App()->SavedSearchManager->getSavedSearchStorage();
                $storage->saveSearch($searchName, $search, $errors);

                if (!empty($errors))
				{
					$this->displayErrors($errors);
				}
				else
				{
                    $this->templateProcessor->assign('savedSearchesCount',$storage->getSearchCount());
					$this->templateProcessor->display("save_search_success.tpl");
				}
			}
		}
		elseif (!$this->isSearchDataExist())
		{
			$this->displayErrors(array('INVALID_SEARCH_ID_PROVIDED'));
		}
		else 
		{
			$this->displayForm();
		}
	}

	private function displayErrors($errors)
	{
		$this->templateProcessor->assign("errors", $errors);
		$this->templateProcessor->display("save_search_failed.tpl");
	}
	private function displayForm()
	{
		$this->templateProcessor->assign("searchId", $_REQUEST['searchId']);
		$this->templateProcessor->display("save_search_form.tpl");
	}

	private function getCurrentSearch()
	{
		return unserialize($this->getRawSearchData());
	}
	
	private function isSearchDataExist()
	{
		return null !== $this->getRawSearchData();
	}
	
	private function getRawSearchData()
	{
		return \App()->Session->getValue($_REQUEST['searchId'], 'SEARCHES');
	}
}
