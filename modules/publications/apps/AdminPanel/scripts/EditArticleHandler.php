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

class EditArticleHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'publications';
	protected $functionName = 'edit_article';

	public function respond()
	{
		$article = \App()->PublicationArticleManager->getObjectBySid(\App()->Request['article_sid']);
		$article->setPropertyValue('date', \App()->I18N->getDateTime($article->getPropertyValue('date')));

		if (\App()->Request['action'] == 'save') {
            $article->incorporateData(\App()->Request->getRequest());
        }
		$form = new \lib\Forms\Form($article);

		if (\App()->Request['action'] == 'save' && $form->isDataValid())
		{
            \App()->PublicationArticleManager->saveObject($article);
			throw new \lib\Http\RedirectException($_SERVER['HTTP_REFERER']);
		}

		$template_processor = \App()->getTemplateProcessor();
		$form->registerTags($template_processor);

		$template_processor->assign('category', \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter(\App()->PublicationCategoryManager->getObjectBySid($article->getCategorySid())));
		$template_processor->assign('article', \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($article));
		$template_processor->assign('form_fields', $form->getFormFieldsInfo());
		$template_processor->display('edit_article.tpl');
	}
}
