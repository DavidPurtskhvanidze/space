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

class DisplayListingHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display Listing';
	protected $moduleName = 'classifieds';
	protected $functionName = 'display_listing';
	protected $parameters = array('display_template');

	public function respond()
	{

		$template_processor = \App()->getTemplateProcessor();
		$displayHelper = \modules\classifieds\lib\Listing\DisplayHelper::getInstance(\App()->Request->getRequest());

		if ($displayHelper->canDisplay())
		{
			$listing = $displayHelper->getListing();
			$addListingProperties = new \core\ExtensionPoint('modules\classifieds\apps\FrontEnd\IAddListingPropertyOnDisplayListing');
			foreach ($addListingProperties as $addListingProperty)
			{
				$addListingProperty->setListing($listing);
				$addListingProperty->perform();
			}

			\App()->ListingManager->incrementViewsCounterForListing($displayHelper->getListingId());

			if (!empty(\App()->Request['searchId']) && !is_null($search = $this->getSearch(\App()->Request['searchId'], $listing)))
			{
                $search->setObjectSid($listing->getSid());
				$template_processor->assign("listing_search", new \lib\ORM\SearchEngine\SearchArrayAdapter($search));
			}
			$display_form = new \lib\Forms\Form($listing);
			$display_form->registerTags($template_processor);
			$template_processor->assign("ancestors", $this->getNavigationElements($listing->getCategorySid()));
			$template_processor->assign("form_fields", $display_form->getFormFieldsInfo());

			$magicFields = new \modules\classifieds\lib\MagicFields($display_form->getFormFieldsInfo());

			$template_processor->assign("magicFields", $magicFields);

			$listingsIdsInComparison = \App()->ObjectMother->createListingComparisonTable()->getListings();
			$template_processor->assign("listingsCountInComparison", count($listingsIdsInComparison));
			$template_processor->assign("savedSearchesCount", \App()->SavedSearchManager->getSavedSearchStorage()->getSearchCount());
			$template_processor->assign("savedListingsCount", count(\App()->ObjectMother->createSavedListings()->getSavedListings()));

			$listingDisplayer = new \modules\classifieds\lib\Listing\ListingDisplayer();
			$listingDisplayer->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);
			$listingDisplayer->setSavedListingsIds(\App()->ObjectMother->createSavedListings()->getSavedListings());
			$listingDisplayer->setListingsIdsInComparison($listingsIdsInComparison);
            if (!((bool)\App()->Request['do_not_modify_meta_data']))
            {
                $this->setPageMeta($listingDisplayer->wrapListing($listing), $listing->getCategorySID());
            }

			$template_processor->assign("listing", $listingDisplayer->wrapListing($listing));
			$template = !empty($_REQUEST['display_template']) ? $_REQUEST['display_template'] : \App()->CategoryManager->getCategoryViewTemplateFileName($listing->getCategorySID());
			$template_processor->assign("listingControlTemplateProviders", new \core\ExtensionPoint('modules\classifieds\apps\FrontEnd\IDisplayListingPageControlTemplateProvider'));
			$template_processor->display($template);
		}
		else
		{
			$template_processor->assign("errors", $displayHelper->getErrors());
			$template_processor->display('category_templates/display/display_errors.tpl');
		}
	}

	private function setPageMeta($listing, $categorySid)
	{
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('listing', $listing);
		$params = array(
			'meta_keywords'		=> 'appendPageKeywords',
			'meta_description'	=> 'appendPageDescription',
			'page_title'		=> 'appendPageTitle'
		);
		foreach ($params as $paramName => $function)
		{
			$param = $this->getListingFieldValueInheritedFromCategory($paramName, $listing, $categorySid);
			$param = $templateProcessor->fetch('string:' . $param);
			$param = strip_tags($param);
			\App()->GlobalTemplateVariable->$function($param);
		}
	}

	private function getListingFieldValueInheritedFromCategory($field, $listing, $categorySid)
	{
		$value = (string) $listing[$field];
		if (empty($value))
			$value = \App()->CategoryManager->getInheritedExtraParameter($categorySid,$field);
		return $value;
	}

	private function getSearch($searchId)
	{
		if (is_null(\App()->Session->getContainer('SEARCHES')->getValue($searchId))) return null;
		$search = unserialize(\App()->Session->getContainer('SEARCHES')->getValue($searchId));
		$search->setDB(\App()->DB);
		$searchMetadata = \App()->Session->getContainer('SEARCHES_METADATA')->getValue($searchId);
		$search->setModelObject($this->getModelListing($searchMetadata['categorySid']));
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		return $search;
	}
	
	private function getModelListing($categorySid)
	{
		$model = \App()->ListingFactory->getListing(array(), $categorySid);
        $model->addFirstActivationDateProperty();

		$onSearchModelListingCreatedActions = new \core\ExtensionPoint('modules\classifieds\apps\FrontEnd\IOnSearchModelListingCreated');
		foreach ($onSearchModelListingCreatedActions as $onSearchModelListingCreatedAction)
		{
			$onSearchModelListingCreatedAction->setModelListing($model);
			$onSearchModelListingCreatedAction->perform();
		}

		return $model;
	}
	
	private function getNavigationElements($categorySid)
	{
		$categoryTree = \App()->ObjectMother->createCategoryManager()->getTree();
		$categoryNames = \App()->DB->query("SELECT `sid`, `name` FROM `classifieds_categories`");
		$categoryTree->addScalarExtraParameters($categoryNames, 'sid', 'name', 'caption');
		
		$navigationElementsUrlDefiner = new NavigationElementsUrlDefiner();
		$navigationElementsUrlDefiner->setBaseUri("/");
		$treeWalker = \App()->ObjectMother->createTreeWalker();
		$treeWalker->setHandler($navigationElementsUrlDefiner);
		$treeWalker->walkUp($categoryTree->getItem($categorySid));
		return $navigationElementsUrlDefiner->getNavigationElements();
	}
}


class NavigationElementsUrlDefiner
{
	private $rootCategoryId = 0;
	private $baseUri;
	/**
	 * @var \modules\miscellaneous\lib\TreeItem[]
	 */
	private $treeItems = array();

	public function setBaseUri($baseUri)
	{
		$this->baseUri = $baseUri;
	}

	/**
	 * @param \modules\miscellaneous\lib\TreeItem $treeItem
	 */
	public function handle($treeItem)
	{
		if ($treeItem->getID() == $this->rootCategoryId)
		{
			$uri = $this->baseUri;
		}
		else
		{
			$uri = $treeItem->getExtraParameter('caption');
			$uri = str_replace(" ", "+", $uri);
			$uri = str_replace('/', '%252F', $uri);
			$uri = $uri . "/";
		}
		$treeItem->addScalarExtraParameter('uri', $uri);
		foreach ($this->treeItems as $ti)
		{
			$ti->addScalarExtraParameter('uri', $uri . $ti->getExtraParameter('uri'));
		}
		$this->treeItems[] = $treeItem;
	}

	public function getNavigationElements()
	{
		$navElements = array();
		foreach ($this->treeItems as $ti)
		{
			$navElements[] = array
			(
				'path' => $ti->getExtraParameter('uri'),
				'caption' => $ti->getExtraParameter('caption'),
			);
		}
		return array_reverse($navElements);
	}
}
