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


namespace modules\publications\apps\FrontEnd\scripts;

class ShowPublicationsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Show publications';
	protected $moduleName = 'publications';
	protected $functionName = 'show_publications';
	protected $parameters = array('category_id', 'article_sid', 'number_of_publications', 'publications_template');

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();

		/**
		 * @var \modules\publications\lib\PublicationCategory $category
		 * @var \modules\publications\lib\PublicationArticle $article
		 */
		list($category, $article) = $this->getCategoryAndArticle();

		if (!is_null($article))
		{
			$template_processor->assign('article', \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($article));
			$template_processor->assign('category', \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($category));
			$defaultTemplate = 'print_article.tpl';
		}
		elseif (!is_null($category))
		{
			$template_processor->assign('category', \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($category));
			$template_processor->assign('articles', \App()->PublicationArticleManager->getCollectionForTemplate($category->getSID(), 'date', 'DESC', \App()->Request['number_of_publications']));
			$defaultTemplate = 'print_articles.tpl';
		}
		else
		{
			$template_processor->assign('categories', \App()->PublicationCategoryManager->getCollectionForTemplate());
			$defaultTemplate = 'print_categories.tpl';
		}

		$template_processor->display(\App()->Request->getValueOrDefault('publications_template', $defaultTemplate));
	}

	/**
	 * @return array
	 */
	private function getCategoryAndArticle()
	{
		if (!empty($_REQUEST['passed_parameters_via_uri']))
		{
			$parametersViaUrl = \App()->UrlParamProvider->getParams();
			$categoryId = isset($parametersViaUrl[0]) ? $parametersViaUrl[0] : null;
			$articleSid = isset($parametersViaUrl[1]) ? $parametersViaUrl[1] : null;
		}
		else
		{
			$categoryId = \App()->Request['category_id'];
			$articleSid = \App()->Request['article_sid'];
		}
		$categorySid = \App()->PublicationCategoryManager->getCategorySidById($categoryId);

		$article = \App()->PublicationArticleManager->getObjectBySid($articleSid);
		$category = \App()->PublicationCategoryManager->getObjectBySid($categorySid);

		return array($category, $article);
	}
}
