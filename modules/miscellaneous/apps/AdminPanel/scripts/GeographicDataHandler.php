<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\apps\AdminPanel\scripts;

class GeographicDataHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\miscellaneous\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'miscellaneous';
	protected $functionName = 'geographic_data';

 	/**
     * @var \modules\smarty_based_template_processor\lib\TemplateProcessor
     */
    private $template_processor;

	public function respond()
	{
		$this->template_processor = \App()->getTemplateProcessor();
		$this->displayForm();
		$this->displayTable();
		$this->template_processor->display("geographic_data.tpl");
	}

	private function displayForm()
	{		
		$search_form_builder = new \lib\ORM\SearchEngine\SearchFormBuilder(\App()->LocationManager->createLocation(array()));
        $requestData = \App()->ObjectMother->createRequestReflector();
        try
		{
			$_REQUEST = array_merge($_REQUEST, $this->getSearch()->getRequest());
		}
		catch (\Exception $e) {}
		$search_form_builder->setRequestData($requestData);
		$search_form_builder->registerTags($this->template_processor);
	}
	
	private function displayTable()
	{
		$search = $this->getSearch();
		$this->template_processor->assign('locations', $this->getLocations($search));
		$this->template_processor->assign('search', new \lib\ORM\SearchEngine\SearchArrayAdapter($search));
		$this->saveSearchToSession($search);
	}

	private $search = null;
	private function getSearch()
	{
		if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'restore')
		{
			$this->search = $this->getSearchFromSession($_REQUEST['searchId']);
			$_REQUEST = array_merge($this->search->getRequest(), $_REQUEST);
		}
		else
		{
			$this->search = new \lib\ORM\SearchEngine\Search();
			$this->search->setId($this->generateSearchId());
			$this->search->setPage(1);
			$this->search->setObjectsPerPage(100);
		}
		if(isset($_REQUEST['items_per_page']))
		{
			$this->search->setObjectsPerPage($_REQUEST['items_per_page']);
		}
		$this->search->setRequest($_REQUEST);
		$this->search->setDB(\App()->DB);
		$locationsFactoryToRowMapperAdapter = new \modules\miscellaneous\lib\Location\LocationsFactoryToRowMapperAdapter();
		$this->search->setRowMapper($locationsFactoryToRowMapperAdapter);
		$this->search->setModelObject(\App()->LocationManager->createLocation(array()));
		$this->search->setCriterionFactory(\App()->SearchCriterionFactory);
		if (isset($_REQUEST['page'])) $this->search->setPage(intval($_REQUEST['page']));
		return $this->search;
	}

	private function getSearchFromSession($searchId)
	{
		$ss = \App()->Session->getContainer('SEARCHES')->getValue($searchId);
		if ($ss) return unserialize($ss);
		throw new \Exception("SEARCH_EXPIRED");
	}

	private function saveSearchToSession($search)
	{
		\App()->Session->getContainer('SEARCHES')->setValue($search->getId(), serialize($search));
	}

	private function setPage($search)
	{
		if (isset($_REQUEST['page'])) $search->setPage(intval($_REQUEST['page']));
	}

	private function getLocations($search)
	{
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
	}
	
	private function generateSearchId()
	{
		return uniqid();
	}

	public function getCaption()
	{
		return "Geographic Locations";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById($this->functionName);
	}

	public function getHighlightUrls()
	{
		return array
		(
            \App()->PageRoute->getPageURLById($this->functionName . '_import_data'),
            \App()->PageRoute->getPageURLById($this->functionName . '_edit_location'),
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'add_geographic_data'),
		);
	}

	public static function getOrder()
	{
		return 300;
	}
}
