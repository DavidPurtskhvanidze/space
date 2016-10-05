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

class RefineSearchHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Refine Search';
	protected $moduleName = 'classifieds';
	protected $functionName = 'refine_search';

	public function respond()
	{
        if (is_null(\App()->Request['search_id']))
        {
            return;
        }
		$template_processor = \App()->getTemplateProcessor();
		$serializedSearch = \App()->Session->getContainer('SEARCHES')->getValue(\App()->Request['search_id']);
		if (!is_null($serializedSearch))
		{
            /** @var $search \lib\ORM\SearchEngine\Search */
            $search = unserialize($serializedSearch);
            $template_processor->assign('search_form_uri', $search->getSearchFormUri()); //For Manage Search Options
			$_REQUEST = array_merge($_REQUEST, $search->getRequest());
		}
        $category_sid = $this->getCategorySid();

		$listing = \App()->ListingFactory->getListing(array(), $category_sid);
		$listing->defineRefineSearchExtraDetailsAttributes();
		$search_form_builder = new \lib\ORM\SearchEngine\SearchFormBuilder($listing);
		$search_form_builder->setRequestData(\App()->ObjectMother->createRequestReflector());
		$search_form_builder->registerTags($template_processor);

        $listingsIdsInComparison = \App()->ObjectMother->createListingComparisonTable()->getListings();
        $savedListings = \App()->ObjectMother->createSavedListings();

		$additionalCriteria = \App()->Session->getValue(\App()->Request['search_id'], 'REFINE_SEARCH_ADDITIONAL_CRITERIA');
		$additionalCriteria = (!empty($additionalCriteria)) ? unserialize($additionalCriteria) : array();
		$template_processor->assign('additionalCriteria',$additionalCriteria);
		
		
        /** @var $storage \modules\classifieds\lib\SavedSearch\ISavedSearchStorage */
        $storage = \App()->SavedSearchManager->getSavedSearchStorage();
        $template_processor->assign("listingsCountInComparison", count($listingsIdsInComparison));
        $template_processor->assign("savedSearchesCount", $storage->getSearchCount());
        $template_processor->assign("savedListingsCount", count($savedListings->getSavedListings()));
		$template_processor->assign('category_sid', $category_sid);
		$template_processor->assign('listing_search', new \lib\ORM\SearchEngine\SearchArrayAdapter($search));
		$template_processor->assign('ignoreFieldIds', (array) \App()->Request['ignoreFieldIds']);
		$template_processor->assign('form_fields', $search_form_builder->getFormFieldsInfo());
        $template_processor->display(\App()->CategoryManager->getCategoryRefineSearchTemplateFileName($category_sid));
	}
	
	private function getCategorySid()
	{
		if (isset($_REQUEST['category_sid']))
		{
			$category_sid = $_REQUEST['category_sid']['tree'][max(array_keys($_REQUEST['category_sid']['tree']))];
		}
		if (is_null($category_sid)) $category_sid = \App()->CategoryManager->getRootId();
		return $category_sid;
	}
}
