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


namespace modules\recent_listings\apps\FrontEnd\scripts;

class DisplayRecentListingsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Recent Listings';
	protected $moduleName = 'recent_listings';
	protected $functionName = 'display_recent_listings';
	protected $parameters = array('recent_listings_template', 'number_of_rows', 'number_of_cols', 'category_id');

	public function respond()
	{
		$template		= empty(\App()->Request['recent_listings_template']) ? 'recent_listings.tpl' : \App()->Request['recent_listings_template'];
		$numberOfRows	= empty(\App()->Request['number_of_rows']) ? 4 : \App()->Request['number_of_rows'];
		$numberOfCols	= empty(\App()->Request['number_of_cols']) ? 1 :  \App()->Request['number_of_cols'];
		$categoryId	= empty(\App()->Request['category_id']) ? 'root' : \App()->Request['category_id'];
		$recentListingsManager = new \modules\recent_listings\lib\RecentListingsManager();
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->filterThenAssign("listings", $recentListingsManager->getRecentListings($numberOfRows * $numberOfCols, $categoryId));
		$templateProcessor->assign("number_of_rows", $numberOfRows);
		$templateProcessor->assign("number_of_cols", $numberOfCols);
		$templateProcessor->display($template);
	}
}
