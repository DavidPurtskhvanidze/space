<?php
/**
 *
 *    Module: listing_feature_featured v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_featured-7.5.0-1
 *    Tag: tags/7.5.0-1@19791, 2016-06-17 13:19:46
 *
 *    This file is part of the 'listing_feature_featured' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\listing_feature_featured\apps\FrontEnd\scripts;

class FeaturedListingsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Featured Listings';
	protected $moduleName = 'listing_feature_featured';
	protected $functionName = 'featured_listings';
	protected $parameters = array('featured_listings_template', 'number_of_rows', 'number_of_cols', 'category_id');

	public function respond()
	{
		$template = empty(\App()->Request['featured_listings_template']) ?  'featured_listings.tpl' : \App()->Request['featured_listings_template'];
		$numberOfRows = empty(\App()->Request['number_of_rows']) ? 1 : \App()->Request['number_of_rows'];
		$numberOfCols = empty(\App()->Request['number_of_cols']) ? 1 : \App()->Request['number_of_cols'];
		$categoryId	= empty(\App()->Request['category_id']) ? 'root' : \App()->Request['category_id'];
		$number_of_listings = $numberOfRows * $numberOfCols;

		$featuredListingManager = new \modules\listing_feature_featured\lib\FeaturedListingManager();
		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign("listings", $featuredListingManager->getFeaturedListings($number_of_listings, $categoryId));
		$templateProcessor->assign("number_of_rows", $numberOfRows);
		$templateProcessor->assign("number_of_cols", $numberOfCols);
		$templateProcessor->display($template);
	}
}
