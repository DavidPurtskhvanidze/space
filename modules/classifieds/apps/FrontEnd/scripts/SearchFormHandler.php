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

class SearchFormHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Search Form';
	protected $moduleName = 'classifieds';
	protected $functionName = 'search_form';
	protected $parameters = array('category_id', 'form_template');

	public function respond()
	{
		$category_sid = null;
		$requestData = \App()->ObjectMother->createRequestReflector();
		if ($requestData->get('action') == 'restore' || $requestData->get('action') == 'refine')
		{
			$ss = \App()->Session->getContainer('SEARCHES')->getValue($requestData->get('searchId'));
			if (!is_null($ss))
			{
				$search = unserialize($ss);
				$_REQUEST = array_merge($_REQUEST, $search->getRequest());
			}
		}
		$category_sid = $this->getCategorySid();

		if (!isset($_REQUEST['category_sid'])) $_REQUEST['category_sid']['tree']['1'] = $category_sid;

		$template_processor = \App()->getTemplateProcessor();

		$listing = \App()->ListingFactory->getListing([], $category_sid);
		$propertiesToExclude = array('user_sid', 'sid', 'type', 'listing_package', 'expiration_date', 'active', 'moderation_status', 'views', 'package', 'user', 'username', 'meta_keywords', 'meta_description', 'page_title');
		array_walk($propertiesToExclude, array($listing, 'deleteProperty'));

		$listing->deletePropertiesByTypes(array('calendar'));
        $listing->details->addPicturesProperty();

		$search_form_builder = new \lib\ORM\SearchEngine\SearchFormBuilder($listing);
		$search_form_builder->setRequestData($requestData);
		$search_form_builder->registerTags($template_processor);

		$template_processor->assign('form_fields', $search_form_builder->getFormFieldsInfo());
		$template_processor->assign('category_sid', $category_sid);

		$root_node = \App()->CategoryTree->getNode(\App()->CategoryManager->getRootId());
		$current_node = \App()->CategoryTree->getNode($category_sid);
		if (is_null($current_node)) $current_node = $root_node;

		if (isset($search))
			$template_processor->assign('listing_search', new \lib\ORM\SearchEngine\SearchArrayAdapter($search));
		$template_processor->assign('categories', $root_node->toArray());
		$template_processor->assign('current_category', $current_node->toArray());

		$search_form = empty($_REQUEST['form_template'])
            ? \App()->CategoryManager->getCategorySearchTemplateFileName($category_sid)
            : $_REQUEST['form_template']
        ;

		$template_processor->display($search_form);
	}

	private function getCategorySid()
	{
		if (isset($_REQUEST['category_id']))
		{
			$category_sid = \App()->CategoryManager->getCategorySIDByID($_REQUEST['category_id']);
		}
		elseif(isset($_REQUEST['category_sid']['tree']) && is_array($_REQUEST['category_sid']['tree']))
		{
			$categoryTreeCriterion = $_REQUEST['category_sid']['tree'];
			ksort($categoryTreeCriterion);

			// get last not empty value from $categoryTreeCriterion
			do
			{
				$category_sid = array_pop($categoryTreeCriterion);
			} while ($category_sid == "" && !empty($categoryTreeCriterion));
		}
		else
		{
			$category_path = empty($_REQUEST['passed_parameters_via_uri']) ? array() : \App()->UrlParamProvider->getParams();
			$category_sid = \App()->CategoryTree->getCategorySidByPath($category_path);
		}
		if (is_null($category_sid)) $category_sid = \App()->CategoryManager->getRootId();
		return $category_sid;
	}
}
