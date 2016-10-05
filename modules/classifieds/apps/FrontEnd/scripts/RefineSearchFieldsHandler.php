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

class RefineSearchFieldsHandler extends \apps\FrontEnd\ContentHandlerBase {

	protected $displayName = 'Refine Search Fields';
	protected $moduleName = 'classifieds';
	protected $functionName = 'refine_search_fields';
	protected $rawOutput = true;

	var $searchId = null;
	var $categorySid = null;
	/**
	 * ListingModel
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	var $listingModel = null;
	var $existedFields = null;
	
	private function mapActionToMethod($map)
	{
		if (!isset($_REQUEST['action']))
			return;
		if (isset($map[$_REQUEST['action']])) {
			call_user_func($map[$_REQUEST['action']]);
		}
	}

	public function respond()
	{
		$this->searchId = \App()->Request->getValueOrDefault('search_id', null);
		$this->categorySid = \App()->Request->getValueOrDefault('category_sid', 0);
		
		$this->existedFields = \App()->Request->getValueOrDefault('form_fields', array());
		if (!is_array($this->existedFields))
			$this->existedFields = explode(',', $this->existedFields);
		
		$this->createListingModel();
		
		$this->mapActionToMethod
				(
				array
					(
					'show_available_fields' => array($this, 'showAvailableFields'),
					'get_field_by_id' => array($this, 'getFieldById'),
				)
		);
	}

	private function createListingModel()
	{
		$this->listingModel = \App()->ListingFactory->getListing(array(), $this->categorySid);
		
		$this->listingModel->defineRefineSearchExtraDetailsAttributes();
		
		$propertiesToExclude = array(
			'user_sid', 'sid', 'type', 'listing_package', 'expiration_date', 'active',
			'moderation_status', 'views', 'package', 'user', 'username',
			'activation_date', 'Video', 'id', 'feature_featured',
			'feature_slideshow', 'feature_youtube',	'feature_highlighted',
			'feature_sponsored', 'feature_youtube_video_id', 'category_sid',
			'category', 'Sold', 'calendar', 'pictures', 'keywords',
			'meta_keywords', 'meta_description', 'page_title'
		);
		array_walk($propertiesToExclude, array($this->listingModel, 'deleteProperty'));

		$this->listingModel->deletePropertiesByTypes(array('calendar', 'file', 'video'));
		
		$properties = array_keys($this->listingModel->getProperties());
		foreach ($this->existedFields as $propertyId)
		{
			if (in_array($propertyId, $properties) !== false)
			{
				$this->listingModel->setPropertyValue($propertyId, true);
				$_REQUEST[$propertyId]['equal'] = 1;
			}
		}
	}
	
	private function showAvailableFields()
	{
		$template_processor = \App()->getTemplateProcessor();

		$template = \App()->Request->getValueOrDefault('refine_search_fields_template', 'classifieds^refine_search/refine_search_fields.tpl');

		$search_form_builder = new \lib\ORM\SearchEngine\SearchFormBuilder($this->listingModel);
		$requestData = \App()->ObjectMother->createRequestReflector();
		$search_form_builder->setRequestData($requestData);
		$search_form_builder->registerTags($template_processor);

		$formFields = $search_form_builder->getFormFieldsInfo();
		ksort($formFields);
		$template_processor->assign('form_fields', $formFields);
		$result = $template_processor->fetch($template);
		echo $result;
	}

	private function getFieldById()
	{
		$template_processor = \App()->getTemplateProcessor();

		$template = \App()->Request->getValueOrDefault('refine_search_field_template', 'classifieds^refine_search/refine_search_field.tpl');

		$fieldId = \App()->Request->getValueOrDefault('field_id', null);

		if (!$this->listingModel->propertyIsSet($fieldId))
		{
			echo json_encode(array());
			die();
		}

		$container = \App()->Session->getContainer('REFINE_SEARCH_ADDITIONAL_CRITERIA');
		$criteria = \App()->Session->getValue($this->searchId, $container->getId());
		$criteria = unserialize($criteria);
		$criteria[] = $fieldId;
		$criteria = array_unique($criteria);
		\App()->Session->setValue($this->searchId, serialize($criteria), $container->getId());
		
		$search_form_builder = new \lib\ORM\SearchEngine\SearchFormBuilder($this->listingModel);
		$requestData = \App()->ObjectMother->createRequestReflector();
		$search_form_builder->setRequestData($requestData);
		$search_form_builder->registerTags($template_processor);

		$template_processor->assign('form_fields', $search_form_builder->getFormFieldsInfo());
		$template_processor->assign('requiredFieldId', $fieldId);
		$result = $template_processor->fetch($template);
		echo json_encode(array('field' => $result, 'fieldId' => $fieldId, 'type' => $this->listingModel->getProperty($fieldId)->getType()));
	}

}
