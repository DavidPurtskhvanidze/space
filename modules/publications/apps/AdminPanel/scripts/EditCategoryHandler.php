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

class EditCategoryHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'publications';
	protected $functionName = 'edit_category';

	public function respond()
	{
		$templateProcessor = \App()->getTemplateProcessor();

		$category = App()->PublicationCategoryManager->getObjectBySid(\App()->Request['category_sid']);
		$category->incorporateData(\App()->Request->getRequest());

		$editForm = new \lib\Forms\Form($category);
		$editForm->registerTags($templateProcessor);

		if (\App()->Request['action'] == 'save' && $editForm->isDataValid())
		{
			\App()->PublicationCategoryManager->saveObject($category);
			throw new \lib\Http\RedirectException(\App()->PageRoute->getPagePathById('publications'));
		}
		elseif (\App()->Request['action'] == 'delete_article')
		{
			$canPerform = true;
			$validators = new \core\ExtensionPoint('modules\publications\apps\AdminPanel\IDeletePublicationArticleValidator');

			/**
			 * @var \modules\publications\apps\AdminPanel\IDeletePublicationArticleValidator $validator
			 */
			foreach ($validators as $validator)
			{
				$validator->setArticleId(\App()->Request['article_sid']);
				$canPerform &= $validator->isValid();
			}

			if ($canPerform)
			{
				\App()->PublicationArticleManager->deletePublicationArticle(\App()->Request['article_sid']);
			}

			throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, $this->functionName) . '?category_sid=' . \App()->Request['category_sid']);
		}

		$pageInfo = array(
				'module' => 'publications',
				'function' => 'show_publications',
				'parameters' => array('category_id' => $category->getID()),
		);

		$formFieldsInfo = $editForm->getFormFieldsInfo();
		unset($formFieldsInfo['order']);

        $sortingField = \App()->Request['sortingField'] ? \App()->Request['sortingField'] : 'date';
        $sortingOrder  = \App()->Request['sortingOrder']? \App()->Request['sortingOrder'] : 'DESC';

		$templateProcessor->assign("category", \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($category));
		$templateProcessor->assign("articles", \App()->PublicationArticleManager->getCollectionForTemplate($category->getSID(), $sortingField, $sortingOrder));
		$templateProcessor->assign("form_fields", $formFieldsInfo);
		$templateProcessor->assign("pageInfo", $pageInfo);
		$templateProcessor->display("show_category.tpl");
	}
}
