<?php
/**
 *
 *    Module: recent_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: recent_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19807, 2016-06-17 13:20:30
 *
 *    This file is part of the 'recent_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\recent_listings\apps\SubDomain\scripts;

class DisplayRecentListingsHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Recent Listings';
	protected $moduleName = 'recent_listings';
	protected $functionName = 'display_recent_listings';
	protected $parameters = array('recent_listings_template', 'number_of_rows', 'number_of_cols');

	public function respond()
	{
		$template		= \App()->Request->getValueOrDefault('recent_listings_template', 'recent_listings.tpl');
		$numberOfRows	= \App()->Request->getValueOrDefault('number_of_rows', 4);
		$numberOfCols	= \App()->Request->getValueOrDefault('number_of_cols', 1);
		$categoryId	= \App()->Request->getValueOrDefault('category_id', 'root');
		$recentListingsManager = new \modules\recent_listings\lib\RecentListingsManager();
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->filterThenAssign("listings", $recentListingsManager->getRecentListings($numberOfRows * $numberOfCols, $categoryId, \App()->Dealer['user_sid']));
		$templateProcessor->assign("number_of_rows", $numberOfRows);
		$templateProcessor->assign("number_of_cols", $numberOfCols);
		$templateProcessor->display($template);
	}
}
