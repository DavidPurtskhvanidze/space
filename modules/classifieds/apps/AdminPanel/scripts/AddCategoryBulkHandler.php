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

class AddCategoryBulkHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'classifieds';
	protected $functionName = 'add_category_bulk';

	public function respond()
	{
		$template_processor = App()->getTemplateProcessor();
		$template_processor->assign('parentCategorySid', \App()->Request['parent']);

		$validationErrors = array();

		// Forms in template displayed based on this variable. So there should be at least one empty category
		$categoriesInfo = array
		(
			array('id' => null, 'name' => ''),
		);

		if (\App()->Request['action'] == 'save')
		{
			$categoriesInfo = \App()->Request['categories'];
			$validationErrors = $this->getValidationErrors($categoriesInfo);

			if (empty($validationErrors))
			{
				foreach ($categoriesInfo as $categoryInfo)
				{
					$categoryInfo['parent'] = \App()->Request['parent'];
					$category = \App()->CategoryManager->getCategory($categoryInfo);
					\App()->CategoryManager->saveCategory($category);
					\App()->ListingFieldManager->copyFieldsOrderFromParent($category);
				}
				$template_processor->display('add_category_bulk_success.tpl');
				return;
			}
			else
			{
				\App()->ErrorMessages->addMessage('VALIDATION_ERROR');
			}
		}

		$template_processor->assign('categoriesInfo', $categoriesInfo);
		$template_processor->assign('validationErrors', $validationErrors);

		$template_processor->display('add_category_bulk_form.tpl');
	}

	private function getValidationErrors($categoriesInfo)
	{
		$validationErrors = $this->validateForDuplicatedIds($categoriesInfo);

		$propertiesToValidate = array('id', 'name');
		foreach ($categoriesInfo as $key => $categoryInfo)
		{
			$category = \App()->CategoryManager->getCategory($categoryInfo);
			foreach ($propertiesToValidate as $propertyId)
			{
				if (!$category->getProperty($propertyId)->isValid())
				{
					$validationErrors[$key][$propertyId] = $category->getProperty($propertyId)->getValidationErrorMessage();
				}
			}
		}

		return $validationErrors;
	}

	private function validateForDuplicatedIds($categoriesInfo)
	{
		$validationErrors = array();
		$categoryIds = array();
		foreach ($categoriesInfo as $k => $categoryInfo)
		{
			$categoryIds[$k] = $categoryInfo['id'];
		}

		$countValues = array_count_values($categoryIds);
		$duplicatedIds = array_keys(array_filter($countValues, function ($count)
		{
			return $count > 1;
		}));

		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('fieldCaption', 'ID');

		foreach ($duplicatedIds as $duplicatedId)
		{
			$duplicatedKeys = array_keys($categoryIds, $duplicatedId);
			foreach ($duplicatedKeys as $duplicatedKey)
			{
				$validationErrors[$duplicatedKey]['id'] = $templateProcessor->fetch('miscellaneous^error_messages/duplicated_value.tpl');
			}
		}
		return $validationErrors;
	}
}
