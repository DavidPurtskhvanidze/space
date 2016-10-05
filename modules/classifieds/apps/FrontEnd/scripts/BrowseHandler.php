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

class BrowseHandler extends \apps\FrontEnd\ContentHandlerBase
{
    protected $displayName = 'Browse';
    protected $moduleName = 'classifieds';
    protected $functionName = 'browse';
    protected $parameters = array('category_id', 'fields', 'number_of_levels', 'number_of_cols', 'browse_template', 'default_sorting_field', 'default_sorting_order', 'default_listings_per_page');

    public function respond()
    {
        $this->decodeRequest();
        $this->_restoreRequest();
        if (!$this->uri_contains_slash_at_the_end())
            $this->redirect_to_uri_with_slash();

        $category_id = $this->get_category_id();

        try {
            $browseManager = \App()->ObjectMother->createBrowseManager($category_id);
            $template_processor = $this->get_template_processor($browseManager, $category_id);

            // We request the total listings number only for the browsing page not for search results page.
            // There is no need to ask searcher for the total listings number if we do not use it.
            if ($browseManager->canBrowse())
            {
                $template_processor->assign('totalListingsNumber', $browseManager->getTotalListingsNumber());
                if (!((bool)\App()->Request['do_not_modify_meta_data']))
                {
                    \App()->CategoryManager->definePageMetaForCategory($browseManager->getListing()->getCategorySid());
                }
            }

            $template_processor->assign('current_page_uri', $this->get_normalized_current_page_uri());
            $template_processor->assign('browse_level', $browseManager->getLevel() + 1);
            $template_processor->assign('browsingFieldIds', $browseManager->getFieldsIds());
            $template_processor->assign('browse_navigation_elements', $browseManager->getNavigationElements($this->get_normalized_current_page_uri()));
            $template_processor->assign('browseItems', $this->get_browse_items($browseManager));
            $template_processor->assign('browseItemsGroupedByColumn', $this->getBrowseItemsGroupedByColumn($browseManager));
            $template_processor->assign("number_of_cols", (isset($_REQUEST['number_of_cols']) ? $_REQUEST['number_of_cols'] : 1));

            $template = $this->get_template();
        } catch (\modules\classifieds\lib\Browse\BrowsingException_InvalidField $e) {
            $errors = array('BROWSING_ERROR_INVALID_FIELD_ID' => $e->getFieldId());

            $template_processor = \App()->getTemplateProcessor();
            $template_processor->assign('ERRORS', $errors);
            $template = 'errors.tpl';
        }
        $template_processor->display($template);
    }

    function _restoreRequest()
    {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'restore') {
            $search = \App()->Session->getContainer('SEARCHES')->getValue($_REQUEST['searchId']);
            if (!is_null($search)) {
                $search = unserialize($search);
                $_REQUEST = array_merge($search->getRequest(), $_REQUEST);
                $metadata = \App()->Session->getContainer('SEARCHES_METADATA')->getValue($search->getId());
                if (!is_null($metadata) && array_key_exists('view_all', $metadata))
                    $_REQUEST['view_all'] = $metadata['view_all'];
            }
        }
    }

    function get_normalized_current_page_uri()
    {
        return \App()->PageManager->getBaseUri();
    }

    function get_template_processor($browseManager, $category_id)
    {
        $request_data = $browseManager->getRequestDataForSearchResults();
        $from_request = $this->getFieldsFromRequest('default_listings_per_page', 'default_sorting_field', 'default_sorting_order', 'sorting_field', 'sorting_order', 'listings_per_page', 'page');
        $request_data = array_merge($from_request, $request_data);

        if (isset(\App()->Request['category_sid']))
        {
            unset($request_data['category_sid']);
        }

        $template_processor = \App()->getTemplateProcessor();
        $template_processor->assign('category', $category_id);

        if ($browseManager->canBrowse()) {
            $browsing_meta_data = $browseManager->getBrowsingMetaData();

            $template_processor->assign('METADATA', $browsing_meta_data);
        }
        $_REQUEST = array_merge($_REQUEST, $request_data);
        $_REQUEST['action'] = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'search';
        return $template_processor;
    }

    function getFieldsFromRequest()
    {
        $fields = func_get_args();
        $res = array();
        foreach ($fields as $field) {
            if (isset($_REQUEST[$field])) {
                $res[$field] = $_REQUEST[$field];
            }
        }
        return $res;
    }

    /**
     * @param \modules\classifieds\lib\Browse\BrowseManager $browseManager
     * @return array
     */
    function get_browse_items($browseManager)
    {
        if ($browseManager->canBrowse())
            return $browseManager->getItems();
        else
            return Array();
    }

    function getBrowseItemsGroupedByColumn($browseManager)
    {
        $browseItemsGroupedByColumn = array();
        $browseItems = $this->get_browse_items($browseManager);
        $numberOfColumns = $this->getNumberOfColumns();
        for ($i = 0; $i < $numberOfColumns; $i++) {
            if (empty($browseItems)) break;
            $numberOfRows = ceil(count($browseItems) / ($numberOfColumns - $i));
            $browseItemsGroupedByColumn[$i] = array_slice($browseItems, 0, $numberOfRows);
            array_splice($browseItems, 0, $numberOfRows);
        }
        return $browseItemsGroupedByColumn;
    }

    private function getNumberOfColumns()
    {
        return isset($_REQUEST['number_of_cols']) ? $_REQUEST['number_of_cols'] : 1;
    }

    function get_category_id()
    {
        return \App()->Request->getValueOrDefault('category_id', '');
    }

    function get_template()
    {
        return \App()->Request->getValueOrDefault('browse_template', 'browse_items_and_results.tpl');
    }

    function get_REQUEST_param_or_default($id_param, $default)
    {
        return !empty($_REQUEST[$id_param]) ? $_REQUEST[$id_param] : $default;
    }

    function uri_contains_slash_at_the_end()
    {
        $uri = parse_url($_SERVER['REQUEST_URI']);
        return preg_match("/\/$/", $uri['path']);
    }

    function redirect_to_uri_with_slash()
    {
        $uri = parse_url($_SERVER['REQUEST_URI']);
        $query = isset($uri['query']) ? '?' . $uri['query'] : '';
        throw new \lib\Http\RedirectException($uri['path'] . "/" . $query);
    }

    private function decodeRequest()
    {
        if (isset($_REQUEST['passed_parameters_via_uri'])) {
            $_REQUEST['passed_parameters_via_uri'] = urldecode($_REQUEST['passed_parameters_via_uri']);
        }
    }
}
