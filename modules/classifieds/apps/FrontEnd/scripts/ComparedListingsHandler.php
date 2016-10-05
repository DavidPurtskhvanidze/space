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

// version 5 wrapper header

class ComparedListingsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Comparison Listings';
	protected $moduleName = 'classifieds';
	protected $functionName = 'compared_listings';
	protected $parameters = array('priority_fields');

    private $_propertiesToExclude;
	public function respond()
	{
        $this->_propertiesToExclude = array('user_sid', 'user_sid', 'sid', 'type', 'category_sid', 'listing_package', 'expiration_date', 'active', 'id', 'moderation_status', 'views', 'package', 'user', 'username', 'keywords', 'meta_keywords', 'meta_description', 'page_title');

        $comparisonTable = \App()->ObjectMother->createListingComparisonTable();
        $searchRequest = $this->getRequest($comparisonTable->getListings());
        $listings = $this->getListings($this->getSearch($searchRequest));

		$template_processor = \App()->getTemplateProcessor();
		$template_processor->assign("compareListIsEmpty", $this->searchResultIsEmpty($listings));
		$template_processor->assign("listings", $listings);
		$mergedListingFields = $this->getMergedListingFields($listings);
		$this->sortListingFields($mergedListingFields);
        $template_processor->assign('mergedListingfields', $mergedListingFields);
        $template_processor->display('compared_listings.tpl');
	}
	private function getSearch($request)
	{
						$search = new \lib\ORM\SearchEngine\Search();
		$search->setPage(1);
		$search->setObjectsPerPage(10000);
		$search->setRequest($request);
		$search->setDB(\App()->DB);
		$search->setRowMapper(new \modules\classifieds\lib\ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
		$search->setModelObject($this->getModelListing());
		$search->setCriterionFactory(\App()->SearchCriterionFactory);

		return $search;
	}
	private function getListings($search)
	{
		$tmpListings = \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
        $listings = array();
        foreach ($tmpListings as $listing)
            $listings[] = $listing;

        return $listings;
	}
	private function getModelListing()
	{
		return \App()->ListingFactory->getListing(array(), 0);
	}
    private function searchResultIsEmpty($listings)
    {
        foreach ($listings as $listing)
            return false;

        return true;
    }
    private function getMergedListingFields($listings)
    {
        $mergedListingFields = array();
        foreach($listings as $listing)
        {
            $objectProperties = $listing->getObject()->getDetails()->getProperties();
            foreach ($objectProperties as $id => $objectProperty)
            {
                if (!in_array($id, $this->_propertiesToExclude))
                {
                    $field = array(
                        'caption'       => $objectProperty->getCaption(),
                        'id'            => $objectProperty->getID(),
                        'order'         => $objectProperty->getOrder(),
                        'type'          => $objectProperty->getType(),
                    );

                    $mergedListingFields[$id] = $field;
                }
            }
        }

        return $mergedListingFields;
    }

	private function sortListingFields(&$fields)
	{
		$priorityFields = \App()->Request['priority_fields'];
		if (!empty($priorityFields))
		{
			$priorityFields = explode(',',$priorityFields);
			$priorityFields = array_map('trim',$priorityFields);
			$priorityFields = array_reverse($priorityFields);
			foreach ($priorityFields as $fieldName)
			{
				if (array_key_exists($fieldName, $fields))
					$this->moveToTop($fields, $fieldName);
			}
		}
	}

	private function moveToTop(&$array, $key)
	{
		$temp = array($key => $array[$key]);
		unset($array[$key]);
		$array = $temp + $array;
	}

	private function getRequest($listingsIds)
	{
        $targetIds = (empty($listingsIds)) ? array(0) : $listingsIds;
        
		return array('sid' => array('in' => $targetIds));
	}
}
?>
