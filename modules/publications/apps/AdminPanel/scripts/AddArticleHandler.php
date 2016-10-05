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

class AddArticleHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'publications';
	protected $functionName = 'add_article';

	public function respond()
	{
		$object = \App()->PublicationArticleManager->createPublicationArticle(\App()->Request->getRequest());
		$object->setCategorySid(\App()->Request['category_sid']);

		$addForm = new \lib\Forms\Form($object);

		if (\App()->Request['action'] == 'save' && $addForm->isDataValid())
		{
			$object->setPropertyValue('date', \App()->I18N->getDateTime(date("Y-m-d H:i:s")));
			\App()->PublicationArticleManager->saveObject($object);

			$object->addCategoryProperty();

			$afterAddPublicationArticleActions = new \core\ExtensionPoint('modules\publications\apps\AdminPanel\IAfterAddPublicationArticle');
			/**
			 * @var \modules\publications\apps\AdminPanel\IAfterAddPublicationArticle $afterAddPublicationArticleAction
			 */
			foreach ($afterAddPublicationArticleActions as $afterAddPublicationArticleAction)
			{
				$afterAddPublicationArticleAction->setArticle($object);
				$afterAddPublicationArticleAction->perform();
			}
			throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath($this->moduleName, 'edit_category') . '?category_sid=' . $object->getCategorySid());
		}

		$template_processor = \App()->getTemplateProcessor();
		$addForm->registerTags($template_processor);
		$template_processor->assign('category', \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter(\App()->PublicationCategoryManager->getObjectBySid($object->getCategorySid())));
		$template_processor->assign("form_fields", $addForm->getFormFieldsInfo());
		$template_processor->display("add_article.tpl");
	}
}
