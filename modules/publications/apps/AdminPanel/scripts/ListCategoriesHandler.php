<?php
/**
 *
 *    Module: publications v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: publications-7.5.0-1
 *    Tag: tags/7.5.0-1@19806, 2016-06-17 13:20:27
 *
 *    This file is part of the 'publications' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\publications\apps\AdminPanel\scripts;

class ListCategoriesHandler extends \apps\AdminPanel\ContentHandlerBase implements \modules\content_management\apps\AdminPanel\IMenuItem
{
	protected $moduleName = 'publications';
	protected $functionName = 'list_categories';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$object = \App()->PublicationCategoryManager->createPublicationCategory(\App()->Request->getRequest());
		$addForm = new \lib\Forms\Form($object);

		if (\App()->Request['action'] == 'save' && $addForm->isDataValid())
		{
			\App()->PublicationCategoryManager->saveObject($object);
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('publications'));
		}
		elseif (\App()->Request['action'] == 'delete_category')
		{
			$canPerform = true;
			$validators = new \core\ExtensionPoint('modules\publications\apps\AdminPanel\IDeletePublicationCategoryValidator');
			/**
			 * @var \modules\publications\apps\AdminPanel\IDeletePublicationCategoryValidator $validator
			 */
			foreach ($validators as $validator)
			{
				$validator->setCategoryId(\App()->Request['category_sid']);
				$canPerform &= $validator->isValid();
			}

			if ($canPerform)
			{
				\App()->PublicationCategoryManager->deletePublicationCategory(\App()->Request['category_sid']);
			}

			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('publications'));
		}
		elseif (\App()->Request['action'] == 'sort')
		{
			\App()->PublicationCategoryManager->setPublicationCategoriesOrder(\App()->Request['sortingOrder']);
			throw new \lib\Http\NoContent();
		}

		$addForm->registerTags($templateProcessor);
		$formFieldsInfo = $addForm->getFormFieldsInfo();
		unset($formFieldsInfo['order']);
		$templateProcessor->assign("form_fields", $formFieldsInfo);

		$templateProcessor->assign('categories', App()->PublicationCategoryManager->getCollectionForTemplate());
		$templateProcessor->display('show_categories.tpl');
	}


	public function getCaption()
	{
		return "Publications";
	}

	public function getUrl()
	{
		return \App()->PageRoute->getPageURLById('publications');
	}

	public function getHighlightUrls()
	{
		return array
		(
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'edit_category'),
            \App()->PageRoute->getSystemPageURL($this->moduleName, 'edit_article'),
		);
	}

	public static function getOrder()
	{
		return 300;
	}
}
