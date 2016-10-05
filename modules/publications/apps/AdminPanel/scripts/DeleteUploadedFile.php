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

class DeleteUploadedFile extends \apps\AdminPanel\ContentHandlerBase
{
	protected $moduleName = 'publications';
	protected $functionName = 'delete_uploaded_file';

	public function respond()
	{
		$arcticleSid = \App()->Request['article_sid'];
		$arcticleInfo = \App()->PublicationArticleManager->getArticleInfoBySID($arcticleSid);
		if (is_null($arcticleSid))
		{
			\App()->ErrorMessages->addMessage('PARAMETERS_MISSED');
		}
		else
		{
			$arcticle = \App()->PublicationArticleManager->createPublicationArticle($arcticleInfo);
            $arcticle->getProperty('picture')->type->delete();
            \App()->PublicationArticleManager->saveObject($arcticle);
			throw new \lib\Http\RedirectException($_SERVER['HTTP_REFERER']);
		}
	}
}
