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


namespace modules\classifieds\apps\AdminPanel\scripts;

class ManageListingsHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\classifieds\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'manage_listings';

	const DEFAULT_SORTING_FIELD = "activation_date";
	const DEFAULT_SORTING_ORDER = "DESC";

	public function respond()
	{

		try
		{
			$this->showForm();
			$this->showSearchResults();
			$this->saveSearchToSession($this->getSearch());
		}
		catch (\Exception $e)
		{
			\App()->ObjectMother->createDisplayTemplateAction("errors.tpl", array('errors' => array($e->getMessage())))->perform();
			return;
		}
	}
	
	private function showForm()
	{		
		$template_processor = \App()->getTemplateProcessor();
		$search_form_builder = new \lib\ORM\SearchEngine\SearchFormBuilder($this->getModelListing());
		$requestData = \App()->ObjectMother->createRequestReflector();
		try
		{
			$_REQUEST = array_merge($_REQUEST, $this->getSearch()->getRequest());
		}
		catch (\Exception $e) {}
		$search_form_builder->setRequestData($requestData);
		$search_form_builder->registerTags($template_processor);
		$category_tree = \App()->CategoryTree;
        $listingSearchExtraFields = new \core\ExtensionPoint('modules\classifieds\apps\AdminPanel\IListingSearchExtraFormField');
        $root_node = $category_tree->getNode(\App()->CategoryManager->getRootId());
        $template_processor->assign('current_category', $this->getCategorySid());
        $template_processor->assign('categories', $root_node->toArray() );
        $template_processor->assign('listingSearchExtraFields', $listingSearchExtraFields );
		$template_processor->display("manage_listings.tpl");
	}

	private function replaceListingPackgeCriterionForDeletedPackage()
	{
		if (isset($_REQUEST['listing_package']['equal']) && $_REQUEST['listing_package']['equal'] == 'deleted' )
		{
			unset($_REQUEST['listing_package']['equal']);
			$_REQUEST['listing_package']['not_in'] = $this->getAllExistingListingPackageIds();
		}
	}
	
	private function getAllExistingListingPackageIds()
	{
		$result = array();
		$properties = $this->getModelListing()->getProperties();
		$vars = $properties['listing_package']->getPropertyVariablesToAssign();
		foreach ($vars['list_values'] as $value) $result[] = $value['id'];
		return $result;
	}
	
	private function showSearchResults()
	{
		if( !empty($_REQUEST['action']) || !empty($_REQUEST['restore']) )
		{
			$template_processor = \App()->getTemplateProcessor();
			$search = $this->getSearch();
            $this->setSortingFields($search);
			$template_processor->assign('listings', $this->getListings($search));
			$template_processor->assign('listing_search', new \lib\ORM\SearchEngine\SearchArrayAdapter($search));
			$membership_plans = \App()->MembershipPlanManager->getAllMembershipPlansInfoWithPackagesInfo();
			$template_processor->assign('sortingFields', $this->getSortingFields());
			$template_processor->assign('membership_plans', $membership_plans);
			$template_processor->assign('messages', isset($_REQUEST['message']) ? array(array('content' => $_REQUEST['message'])) : array());
			$template_processor->assign('listingMassActions', new \core\ExtensionPoint('modules\classifieds\lib\IListingMassAction'));
			$template_processor->display("display_results.tpl");
		}
	}
	private function getSortingFields()
	{
		$listingProperties = $this->getModelListing()->getDetails()->getProperties();
		$sortableListingProperties = array_filter($listingProperties, array($this, 'isPropertySortable'));

		$systemProperties = array();
		$categoryProperties = array();
		foreach ($sortableListingProperties as $key => $property)
			if ($this->isPropertySystem($property))
				$systemProperties[$key] = $property;
			else
				$categoryProperties[$key] = $property;

		$sortingFields['system'] = array_map(create_function('$property', 'return $property->getCaption();'), $systemProperties);
		$sortingFields['category'] = array_map(create_function('$property', 'return $property->getCaption();'), $categoryProperties);

		return $sortingFields;
	}
	private function isPropertySystem(\lib\ORM\ObjectProperty $property)
	{
		$systemProperties = array('id', 'active', 'views', 'pictures', 'activation_date', 'feature_featured', 'feature_highlighted', 'feature_slideshow', 'feature_youtube', 'feature_sponsored');
		return in_array($property->getID(), $systemProperties);
	}
	private function isPropertySortable(\lib\ORM\ObjectProperty $property)
	{
		$systemPropertiesToInclude = array('id', 'active', 'views', 'pictures', 'activation_date', 'feature_featured', 'feature_highlighted', 'feature_slideshow', 'feature_youtube', 'feature_sponsored');
		$typesToExclude = array('text', 'integer', 'float', 'rating', 'calendar');
		if ($property->isSystem())
		{
			$sortable = in_array($property->getID(), $systemPropertiesToInclude);
		}
		else
		{
			$sortable = !in_array($property->getType(), $typesToExclude);
		}
		return $sortable;
	}

	private $search = null;
	private function getSearch()
	{
		$this->replaceListingPackgeCriterionForDeletedPackage();
		if (is_null($this->search))
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
				$this->search->setObjectsPerPage(self::$DEFAULT_ITEMS_PER_PAGE);
				$this->search->setSearchFormUri($this->getSearchFormUri());
				$this->search->setSearchResultsUri(\App()->Navigator->getURI());
				$this->search->setSortingFields(array(self::DEFAULT_SORTING_FIELD => self::DEFAULT_SORTING_ORDER));
			}
			$this->search->setRequest($_REQUEST);
			$this->search->setDB(\App()->DB);
			$listingsFactoryToRowMapperAdapter = new \modules\classifieds\lib\ListingsFactoryToRowMapperAdapter(\App()->ListingFactory);
			$listingExtraPropertySetters = new \core\ExtensionPoint('modules\classifieds\apps\AdminPanel\IListingExtraPropertySetterOnSearchListing');
			foreach ($listingExtraPropertySetters as $listingExtraPropertySetter)
			{
				$listingsFactoryToRowMapperAdapter->addListingExtraPropertySetter($listingExtraPropertySetter);
			}
			$this->search->setRowMapper($listingsFactoryToRowMapperAdapter);
			$this->search->setModelObject($this->getModelListing());
			$this->search->setCriterionFactory(\App()->SearchCriterionFactory);
			if (isset($_REQUEST['page'])) $this->search->setPage(intval($_REQUEST['page']));
			if (isset($_REQUEST['items_per_page'])) $this->search->setObjectsPerPage(intval($_REQUEST['items_per_page']));
		}
		return $this->search;
	}
    
    private function setSortingFields($search)
    {
		if (isset($_REQUEST['sorting_fields']) && is_array($_REQUEST['sorting_fields']))
            $search->setSortingFields($_REQUEST['sorting_fields']);
    }

	private function getSearchFromSession($searchId)
	{
		$ss = \App()->Session->getContainer('SEARCHES')->getValue($searchId);
		if ($ss) return unserialize($ss);
		throw new \Exception("SEARCH_EXPIRED");
	}

	private $modelListing = null;
	private function getModelListing()
	{
		if (is_null($this->modelListing)) $this->modelListing = \App()->ListingFactory->getListing(array(), $this->getCategorySid());
		return $this->modelListing;
	}

	private function saveSearchToSession($search)
	{
		\App()->Session->getContainer('SEARCHES')->setValue($search->getId(), serialize($search));
		\App()->Session->getContainer('SEARCHES_METADATA')->setValue($search->getId(), array('categorySid' => $this->getCategorySid()));
	}

	private function setPage($search)
	{
		if (isset($_REQUEST['page'])) $search->setPage(intval($_REQUEST['page']));
	}

	private function setListingsPerPage($search)
	{
		if (isset($_REQUEST['items_per_page'])) $search->setObjectsPerPage(intval($_REQUEST['items_per_page']));
	}

	private function getListings($search)
	{
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
	}

	private function getCategorySid()
	{
		if ( isset($_REQUEST['category_sid']) )
		{
			if (is_array($_REQUEST['category_sid']))
			{
				$category_sid = end($_REQUEST['category_sid']['tree']);
			}
			else
			{
				$category_sid = $_REQUEST['category_sid'];
			}
		}
		else
		{
			$category_sid = \App()->CategoryManager->getRootId();
		}
		return $category_sid;
	}

	private static $DEFAULT_ITEMS_PER_PAGE = 10;
	
	private function generateSearchId()
	{
		return uniqid();
	}

	private function getSearchFormUri()
	{
		$uri = \App()->PageManager->getRequestedUri();
		return $uri;
	}

	public static function getOrder()
	{
		return 300;
	}

	public function getCaption()
	{
		return "Manage Listings";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('manage_listings');
	}

	public function getHighlightUrls()
	{
		return array
		(
            \App()->PageRoute->getPageURLById('edit_listing'),
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'manage_pictures'),
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'edit_picture'),
		);
	}
}
