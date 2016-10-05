<?php
/**
 *
 *    Module: form_manager v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: form_manager-7.5.0-1
 *    Tag: tags/7.5.0-1@19783, 2016-06-17 13:19:26
 *
 *    This file is part of the 'form_manager' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\form_manager\apps\FrontEnd\scripts;

class SearchFormHandler extends \modules\classifieds\apps\FrontEnd\scripts\SearchFormHandler
{
	protected $moduleName = 'form_manager';
	protected $functionName = 'display_search_form';
	protected $parameters = array('form_id', 'form_template');

	public function respond()
	{
		if(!$form_id = \App()->Request->getValueOrDefault('form_id'))
		{
			return;
		}
		$appId = \App()->SystemSettings['ApplicationID'];
		$form_info = \App()->FormManager->getFormInfoByID($form_id, $appId);
		$fields_to_display = array();
		$form_fields_info = \App()->FormManager->getFieldsInfoByFormSid($form_info['sid']);
		foreach ($form_fields_info as $key => $value)
		{
			$fields_to_display[] = array('value' => $value['field_id'], 'caption' => $value['caption']);
		}

		if (\App()->Request['action'] == 'restore')
		{
			$ss = \App()->Session->getContainer('SEARCHES')->getValue(\App()->Request['searchId']);
			if (!is_null($ss))
			{
				$search = unserialize($ss);
				$_REQUEST = array_merge($_REQUEST, $search->getRequest());
			}
		}


		$category_sid = $form_info['category_sid'];
		$requestData = \App()->ObjectMother->createRequestReflector();

		$template_processor = \App()->getTemplateProcessor();
		$listing = \App()->ListingFactory->getListing(array(), $category_sid);
		
		/*
			to display "With Pictures" and "Keywords Search" at search form. not "Pictures" and "Keywords"
		*/
		$listing->deleteProperty('pictures');
		$listing->addProperty
        (
            array
            (
				'id'		=> 'pictures',
				'type'		=> 'pictures',
				'is_system' => true,
				'caption'	=> 'With Pictures',
				'value'		=> '',
                'width'     => 0,
                'height'    => 0,
                'table' => 'classifieds_listings_pictures',
                'key' => 'listing_sid'
			)
		);
		$listing->deleteProperty('keywords');
		$listing->addProperty
        (
            array
			(
				'id'		=> 'keywords',
				'type'		=> 'text',
				'value'		=> '',
				'is_system' => true,
				'caption'	=> 'Keywords Search',
				'autocomplete_service_name' => 'ListingManager',
				'autocomplete_method_name' => 'ListingKeywords'
			)
		);
		$search_form_builder = new \lib\ORM\SearchEngine\SearchFormBuilder($listing);
		$search_form_builder->setRequestData($requestData);
		$search_form_builder->registerTags($template_processor);
		$template_processor->assign('form_info', \App()->FormManager->getFormInfoByID($form_id, $appId));
		$template_processor->assign('form_fields', $search_form_builder->getFormFieldsInfo());
		$template_processor->assign('fields_to_display', $fields_to_display);
		$template_processor->assign('category_sid', $category_sid);

		$root_node = \App()->CategoryTree->getNode(\App()->CategoryManager->getRootId());
		$current_node = \App()->CategoryTree->getNode($category_sid);
		if (is_null($current_node)) $current_node = $root_node;

		$template_processor->assign('categories', $root_node->toArray());
		$template_processor->assign('current_category', $current_node->toArray());
		
		$search_form = empty($_REQUEST['form_template']) ? 'search_form.tpl' : $_REQUEST['form_template']
        ;
		$template_processor->display($search_form);
	}
}
