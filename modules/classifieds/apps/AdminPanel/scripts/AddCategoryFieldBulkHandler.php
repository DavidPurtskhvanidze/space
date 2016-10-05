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

class AddCategoryFieldBulkHandler extends \apps\AdminPanel\ContentHandlerBase
{

	protected $moduleName = 'classifieds';
	protected $functionName = 'add_category_field_bulk';

	public function respond()
	{
		$template_processor = App()->getTemplateProcessor();
		$template_processor->assign('category_sid', \App()->Request['category_sid']);
		$template_processor->assign('listingFieldTypes', \App()->ListingFieldManager->getListingFieldTypes());

		$validationErrors = array();

		// Forms in template displayed based on this variable. So there should be at least one empty field
		$fieldsInfo = array
		(
			array('id' => null, 'caption' => null, 'type' => null),
		);

		if (\App()->Request['action'] == 'save')
		{
			$fieldsInfo = \App()->Request['fields'];
			$validationErrors = $this->getValidationErrors($fieldsInfo, \App()->Request['category_sid']);

			if (empty($validationErrors))
			{
				foreach ($fieldsInfo as $fieldInfo)
				{
					$fieldInfo['parent'] = \App()->Request['parent'];
					$listingField = \App()->ListingFieldManager->createListingField($fieldInfo, \App()->Request['category_sid']);

					\App()->ListingFieldManager->addColumnToListingTableForField($listingField);
					\App()->ListingFieldManager->saveListingField($listingField);
					\App()->ListingFieldManager->addListingFieldToOrderTable($listingField);
				}
				$template_processor->display('add_category_field_bulk_success.tpl');
				return;
			}
			else
			{
				\App()->ErrorMessages->addMessage('VALIDATION_ERROR');
			}
		}

		$template_processor->assign('fieldsInfo', $fieldsInfo);
		$template_processor->assign('validationErrors', $validationErrors);

		$template_processor->display('add_category_field_bulk.tpl');
	}

	private function getValidationErrors($fieldsInfo, $categorySid)
	{
		$validationErrors = $this->validateForDuplicatedIds($fieldsInfo);

		$propertiesToValidate = array('id', 'caption', 'type');
		foreach ($fieldsInfo as $key => $fieldInfo)
		{
			$listingField = \App()->ListingFieldManager->createListingField($fieldInfo, $categorySid);
			foreach ($propertiesToValidate as $propertyId)
			{
				if (!$listingField->getProperty($propertyId)->isValid())
				{
					$validationErrors[$key][$propertyId] = $listingField->getProperty($propertyId)->getValidationErrorMessage();
				}
			}
		}

		return $validationErrors;
	}

	private function validateForDuplicatedIds($fieldsInfo)
	{
		$validationErrors = array();
		$fieldIds = array();
		foreach ($fieldsInfo as $k => $fieldInfo)
		{
			$fieldIds[$k] = $fieldInfo['id'];
		}

		$countValues = array_count_values($fieldIds);
		$duplicatedIds = array_keys(array_filter($countValues, function ($count)
		{
			return $count > 1;
		}));

		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('fieldCaption', 'ID');

		foreach ($duplicatedIds as $duplicatedId)
		{
			$duplicatedKeys = array_keys($fieldIds, $duplicatedId);
			foreach ($duplicatedKeys as $duplicatedKey)
			{
				$validationErrors[$duplicatedKey]['id'] = $templateProcessor->fetch('miscellaneous^error_messages/duplicated_value.tpl');
			}
		}
		return $validationErrors;
	}
}
