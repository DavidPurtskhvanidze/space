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


namespace modules\classifieds\apps\MobileFrontEnd\scripts;

class SearchResultsHandler extends \apps\MobileFrontEnd\ContentHandlerBase
{
	protected $displayName = 'Search Form';
	protected $moduleName = 'classifieds';
	protected $functionName = 'search_results';
	protected $parameters = array('default_sorting_field', 'default_sorting_order', 'default_listings_per_page', 'results_template', 'advanced_search_form_uri');

	public function respond()
	{
		$requesData = \App()->ObjectMother->createRequestReflector();
		if (is_null($requesData->get('action')))
		{
			$action = \App()->ObjectMother->createDisplayTemplateAction("errors.tpl", array('ERRORS' => array('PARAMETERS_MISSED')));
			$action->perform();
			return;
		}
		$templateProcessor = \App()->getTemplateProcessor();
		$this->registerResources($templateProcessor);

		$searchListingsHelper = new \modules\classifieds\lib\SearchListingsHelper();
        if (!((bool)\App()->Request['do_not_modify_meta_data']))
        {
            \App()->CategoryManager->definePageMetaForCategory($searchListingsHelper->getCategorySid());
        }

		$templateProcessor->display($this->getTemplateName());
	}
	
	public function getTemplateName()
	{
		return isset($_REQUEST['results_template']) ? $_REQUEST['results_template'] : "search_results.tpl";
	}
	
	public function registerResources($templateProcessor)
	{
		$search = $this->getSearch();
		
		$beforeListingSearchExecutedActions = new \core\ExtensionPoint('modules\classifieds\apps\MobileFrontEnd\IBeforeListingSearchExecuted');
		foreach ($beforeListingSearchExecutedActions as $beforeListingSearchExecutedAction)
		{
			$beforeListingSearchExecutedAction->setSearch($search);
			$beforeListingSearchExecutedAction->perform();
		}

		$searchResultCollection = $search->getFoundObjectCollection();

		$afterListingSearchExecutedActions = new \core\ExtensionPoint('modules\classifieds\apps\MobileFrontEnd\IAfterListingSearchExecuted');
		foreach ($afterListingSearchExecutedActions as $afterListingSearchExecutedAction)
		{
			$afterListingSearchExecutedAction->setSearchResultCollection($searchResultCollection);
			$afterListingSearchExecutedAction->perform();
		}
		
		$templateProcessor->assign("listing_search", new \lib\ORM\SearchEngine\SearchArrayAdapter($search));
		$templateProcessor->assign("listings", $searchResultCollection);
		
		$listingDisplayer = new \modules\classifieds\lib\Listing\ListingDisplayer();
		$listingDisplayer->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);
		$listingDisplayer->setCategoryManager(\App()->CategoryManager);
		$listingDisplayer->setTemplateProcessor(\App()->getTemplateProcessor());
		$listingDisplayer->registerResources($templateProcessor);
		$this->saveSearchToSession($search);
	}
	
	private function setPage($search)
	{
		if (isset($_REQUEST['page'])) $search->setPage(intval($_REQUEST['page']));
	}

	private function setListingsPerPage($search)
	{
		if ($objectsPerPage = (int) \App()->Request->getValueOrDefault('listings_per_page'))
            $search->setObjectsPerPage($objectsPerPage);
		elseif ($objectsPerPage = (int) \App()->Request->getValueOrDefault('default_listings_per_page'))
            $search->setObjectsPerPage($objectsPerPage);
	}

    private function setSortingFields($search)
    {
		if (($sortingFields = \App()->Request->getValueOrDefault('sorting_fields')) && is_array($sortingFields))
            $search->setSortingFields($sortingFields);
		else if ($sortingFields = \App()->Request->getValueOrDefault('default_sorting_field'))
			$search->setSortingFields(array($sortingFields => \App()->Request->getValueOrDefault('default_sorting_order', 'ASC')));
	}

	private function getSearch()
	{
		if ($_REQUEST['action'] == 'restore')
		{
			$search = $this->getSearchFromSession($_REQUEST['searchId']);
			$_REQUEST = array_merge($search->getRequest(), $_REQUEST);
		}
		else
		{
			$search = new \lib\ORM\SearchEngine\Search();
			$search->setId($this->generateSearchId());
			$search->setPage(1);
			$search->setObjectsPerPage(self::$DEFAULT_LISTINGS_PER_PAGE);
			$search->setSearchFormUri($this->getSearchFormUri());
			$search->setSearchResultsUri(\App()->Navigator->getURI());
		}
		$activeOnly = isset($_REQUEST['active_only']) ? $_REQUEST['active_only'] : 1;
		if ($activeOnly) $_REQUEST['active']['equal'] = 1;
		
		$search->setRequest($this->filterOutRequestDataForSearch($_REQUEST));
		$search->setDB(\App()->DB);
		$search->setRowMapper(new \modules\classifieds\lib\ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
		$search->setModelObject($this->getModelListing());
		$search->setCriterionFactory(\App()->SearchCriterionFactory);

		$this->setListingsPerPage($search);
		$this->setPage($search);
        $this->setSortingFields($search);
		
		if ($_REQUEST['action'] != 'restore')
		{
			$onNewListingSearchActions = new \core\ExtensionPoint('modules\classifieds\apps\MobileFrontEnd\IOnNewListingSearch');
			foreach ($onNewListingSearchActions as $onNewListingSearchAction)
			{
				$onNewListingSearchAction->setSearch($search);
				$onNewListingSearchAction->perform();
			}
			
		}
		
		return $search;
	}
	
    private function filterOutRequestDataForSearch($requestData)
    {
		$garbage = array('featureActivatedForListing', 'featureActivated', 'listingActivated', 'action');
		$filtered = array_diff_key($requestData, array_flip($garbage));
		return $filtered;
    }

	private function getSearchFromSession($searchId)
	{
		$ss = \App()->Session->getContainer('SEARCHES')->getValue($searchId);
		return unserialize($ss);
	}
	
	private $modelListing = null;
	private function getModelListing()
	{
		if (!isset($this->modelListing))
		{
			$this->modelListing = \App()->ListingFactory->getListing(array(), $this->getCategorySid());

            /**
             * for condition pictures[more] = 0
             * We set pictures type to integer
             */
            $this->modelListing->addProperty([
                'id'		=> 'pictures',
                'type'		=> 'integer',
                'is_system' => false,
            ]);

			$onSearchModelListingCreatedActions = new \core\ExtensionPoint('modules\classifieds\apps\MobileFrontEnd\IOnSearchModelListingCreated');
			foreach ($onSearchModelListingCreatedActions as $onSearchModelListingCreatedAction)
			{
				$onSearchModelListingCreatedAction->setModelListing($this->modelListing);
				$onSearchModelListingCreatedAction->perform();
			}
		}

		return $this->modelListing;
	}

	private function saveSearchToSession($search)
	{
		\App()->Session->getContainer('SEARCHES')->setValue($search->getId(), serialize($search));
		\App()->Session->getContainer('SEARCHES_METADATA')->setValue($search->getId(), array('categorySid' => $this->getCategorySid()));
	}

	private function getSearchFormUri()
	{
		$advancedSearchFormUri = $this->getAdvancedSearchFormUri();
		if (is_null($advancedSearchFormUri)) return null;
		$typeNode = \App()->CategoryTree->getNode($this->getCategorySid());
		if (!is_null($typeNode))
		{
			$categoryData = $typeNode->toArray();	
			$path = $categoryData['path'];
		}
		else
		{
			$path = '/';
		}
		return \App()->Path->combineURL($advancedSearchFormUri, $path) . "/";
	}
	private function getAdvancedSearchFormUri()
	{
		return !empty($_REQUEST['advanced_search_form_uri']) ? $_REQUEST['advanced_search_form_uri'] : null;
	}
	
	private function getCategorySid()
	{
		if (!isset($_REQUEST['category_sid']['tree'])) return  0;
		$requestedCategoriesSid = $_REQUEST['category_sid']['tree'];
		return $requestedCategoriesSid[max(array_keys($requestedCategoriesSid))];
	}

	private static $DEFAULT_LISTINGS_PER_PAGE = 10;
	
	private function generateSearchId()
	{
		return uniqid();
	}
}
