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


namespace modules\classifieds\lib;

class SearchListingsHelper
{
    public function getSearchFormUri()
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

    public function getCategorySid()
    {
        return $this->getCategorySidFromArray($_REQUEST);
    }
	
	public function getCategorySidFromArray($requestArray)
	{
        if (!isset($requestArray['category_sid']['tree'])) return 0;
        $requestedCategoriesSid = $requestArray['category_sid']['tree'];
        return $requestedCategoriesSid[max(array_keys($requestedCategoriesSid))];
	}
}
