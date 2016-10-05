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


namespace modules\classifieds\apps\SubDomain\scripts;

class SearchResultsHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Search Form';
	protected $moduleName = 'classifieds';
	protected $functionName = 'search_results';
	protected $parameters = array('default_sorting_field', 'default_sorting_order', 'default_listings_per_page', 'results_template', 'advanced_search_form_uri');

    protected $resultViewTypeTemplate = null;
    protected $resultViewTypeControls = array();

    public function respond()
    {
        // action is search by default
        if (!in_array(\App()->Request['action'], array('search', 'restore', 'refine')))
        {
            \App()->Request['action'] = 'search';
        }
        try
        {
            $_REQUEST['user_sid']['equal'] = \App()->Dealer['user_sid'];

            $templateProcessor = \App()->getTemplateProcessor();
            $this->registerResources($templateProcessor);

            $searchListingsHelper = new \modules\classifieds\lib\SearchListingsHelper();
            \App()->CategoryManager->definePageMetaForCategory($searchListingsHelper->getCategorySid());

            $templateProcessor->display($this->getTemplateName());
        }
        catch (\Exception $e)
        {
            \App()->ObjectMother->createDisplayTemplateAction("errors.tpl", array('ERRORS' => array($e->getMessage())))->perform();
            return;
        }
    }

    public function getTemplateName()
    {
        return $this->resultViewTypeTemplate;
    }

    /**
     * @param \modules\smarty_based_template_processor\lib\TemplateProcessor $templateProcessor
     */
    public function registerResources($templateProcessor)
    {
        $search = $this->getSearch();
        $this->registerSearchResultViewTypeData($search);

        $beforeListingSearchExecutedActions = new \core\ExtensionPoint('modules\classifieds\apps\SubDomain\IBeforeListingSearchExecuted');
        foreach ($beforeListingSearchExecutedActions as $beforeListingSearchExecutedAction)
        {
            $beforeListingSearchExecutedAction->setSearch($search);
            $beforeListingSearchExecutedAction->perform();
        }

        $searchResultCollection = $search->getFoundObjectCollection();

        $afterListingSearchExecutedActions = new \core\ExtensionPoint('modules\classifieds\apps\SubDomain\IAfterListingSearchExecuted');
        foreach ($afterListingSearchExecutedActions as $afterListingSearchExecutedAction)
        {
            $afterListingSearchExecutedAction->setSearchResultCollection($searchResultCollection);
            $afterListingSearchExecutedAction->perform();
        }

        $templateProcessor->assign("resultViewTypeControls", $this->resultViewTypeControls);
        $templateProcessor->assign("listing_search", new \lib\ORM\SearchEngine\SearchArrayAdapter($search));
        $templateProcessor->assign("listings", $searchResultCollection);

        $listingsIdsInComparison = \App()->ObjectMother->createListingComparisonTable()->getListings();
        $templateProcessor->assign("listingsCountInComparison", count($listingsIdsInComparison));

        $listingDisplayer = new \modules\classifieds\lib\Listing\ListingDisplayer();
        $listingDisplayer->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);
        $listingDisplayer->setCategoryManager(\App()->CategoryManager);
        $listingDisplayer->setTemplateProcessor(\App()->getTemplateProcessor());
        $listingDisplayer->registerResources($templateProcessor);
        $listingDisplayer->setSavedListingsIds(\App()->ObjectMother->createSavedListings()->getSavedListings());
        $listingDisplayer->setListingsIdsInComparison($listingsIdsInComparison);
        $this->saveSearchToSession($search);
    }

    private function setPage($search)
    {
        if (isset($_REQUEST['page'])) $search->setPage(intval($_REQUEST['page']));
    }

    /**
     * @param $search \lib\ORM\SearchEngine\Search
     */
    private function setListingsPerPage($search)
    {
        if (\App()->Request['action'] == 'search' && !is_null(\App()->Request['default_listings_per_page']))
        {
            $search->setObjectsPerPage(\App()->Request['default_listings_per_page']);
        }

        if (!is_null(\App()->Request['listings_per_page']))
        {
            $search->setObjectsPerPage(\App()->Request['listings_per_page']);
        }
    }

    /**
     * @param $search \lib\ORM\SearchEngine\Search
     */
    private function setSortingFields($search)
    {
        if (\App()->Request['action'] == 'search' && !is_null(\App()->Request['default_sorting_field']))
        {
            $search->setSortingFields(array(\App()->Request['default_sorting_field'] => \App()->Request->getValueOrDefault('default_sorting_order', 'ASC')));
        }

        if (!is_null(\App()->Request['sorting_fields']) and is_array(\App()->Request['sorting_fields']))
        {
            $search->setSortingFields(\App()->Request['sorting_fields']);
        }
    }

    private function getSearch()
    {
        if ($_REQUEST['action'] == 'restore' or $_REQUEST['action'] == 'refine')
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
            $searchListingsHelper = new \modules\classifieds\lib\SearchListingsHelper();
            $search->setSearchFormUri($searchListingsHelper->getSearchFormUri());
            $search->setSearchResultsUri(\App()->Request['do_not_set_search_results_uri'] ? '/search-results/' : \App()->Navigator->getURI());
            $search->setResultViewType(\modules\classifieds\apps\FrontEnd\SearchResultListViewOption::getOptionId());
        }
        $activeOnly = isset($_REQUEST['active_only']) ? $_REQUEST['active_only'] : 1;
        if ($activeOnly) $_REQUEST['active']['equal'] = 1;

        $search->setRequest($this->filterOutRequestDataForSearch($_REQUEST));
        $search->setDB(\App()->DB);
        $search->setRowMapper(new \modules\classifieds\lib\ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
        $search->setModelObject($this->getModelListing());
        $search->setCriterionFactory(\App()->SearchCriterionFactory);
        if (\App()->Request['result_view_type'])
        {
            $search->setResultViewType(\App()->Request['result_view_type']);
        }

        $this->setListingsPerPage($search);
        $this->setPage($search);
        $this->setSortingFields($search);

        if ($_REQUEST['action'] != 'restore')
        {
            $onNewListingSearchActions = new \core\ExtensionPoint('modules\classifieds\apps\FrontEnd\IOnNewListingSearch');
            foreach ($onNewListingSearchActions as $onNewListingSearchAction)
            {
                $onNewListingSearchAction->setSearch($search);
                $onNewListingSearchAction->perform();
            }

        }

        return $search;
    }

    /**
     * Filter out the request data for search
     *
     * Search uses the request data for building criteria.
     * This method leaves only data related to listing properties.
     *
     * @param $requestData
     * @return array
     */
    private function filterOutRequestDataForSearch($requestData)
    {
        return array_intersect_key($requestData, $this->getModelListing()->getProperties());
    }

    private function getSearchFromSession($searchId)
    {
        $ss = \App()->Session->getContainer('SEARCHES')->getValue($searchId);
        if ($ss)
        {
            $search = unserialize($ss);
            $searchListingsHelper = new \modules\classifieds\lib\SearchListingsHelper();
            $searchFromURI = $searchListingsHelper->getSearchFormUri();
            if (\App()->Request['get_search_form_uri'] and !empty($searchFromURI))
                $search->setSearchFormUri($searchFromURI);
            return $search;
        }
        throw new \Exception("SEARCH_EXPIRED");
    }

    private $modelListing = null;
    private function getModelListing()
    {
        if (!isset($this->modelListing))
        {
            $searchListingsHelper = new \modules\classifieds\lib\SearchListingsHelper();
            $this->modelListing = \App()->ListingFactory->getListing(array(), $searchListingsHelper->getCategorySid());

            $onSearchModelListingCreatedActions = new \core\ExtensionPoint('modules\classifieds\apps\FrontEnd\IOnSearchModelListingCreated');
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
        $searchListingsHelper = new \modules\classifieds\lib\SearchListingsHelper();
        $metadata = array('categorySid' => $searchListingsHelper->getCategorySid());
        $savedMetadata = \App()->Session->getContainer('SEARCHES_METADATA')->getValue($search->getId());
        if (\App()->Request->offsetExists('view_all'))
            $metadata['view_all'] = \App()->Request['view_all'];
        elseif(!is_null($savedMetadata) && array_key_exists('view_all', $savedMetadata))
            $metadata['view_all'] = $savedMetadata['view_all'];
        \App()->Session->getContainer('SEARCHES_METADATA')->setValue($search->getId(), $metadata);
    }

    private static $DEFAULT_LISTINGS_PER_PAGE = 10;

    private function generateSearchId()
    {
        return uniqid();
    }

    public function registerSearchResultViewTypeData($search)
    {
        $this->resultViewTypeControls = array();

        $viewTypes = new \core\ExtensionPoint('modules\classifieds\apps\SubDomain\ISearchResultViewTypeOption');
        foreach ($viewTypes as $viewType)
        {
            $viewType->setSearch($search);

            $this->resultViewTypeControls[$viewType->getOptionId()] = $viewType->getRenderedOption();
            if ($viewType->getOptionId() == $search->getResultViewType())
            {
                $this->resultViewTypeTemplate = $viewType->getSearchResultTemplateName();
            }
        }
    }
}
