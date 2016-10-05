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


namespace modules\publications\apps\AdminPanel;

/**
 * After add article action interface
 * 
 * Interface designed for performing action just after article added.
 * 
 * @category ExtensionPiont
 */
interface IAfterAddPublicationArticle
{
	/**
	 * Article setter
	 * @param \modules\publications\lib\PublicationArticle $article
	 */
	public function setArticle($article);
	/**
	 * Action executer
	 */
	public function perform();
}
