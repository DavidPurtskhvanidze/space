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


namespace modules\listing_feature_featured\apps\SubDomain\scripts;

class FeaturedListingsHandler extends \apps\SubDomain\ContentHandlerBase
{
	protected $displayName = 'Featured Listings';
	protected $moduleName = 'listing_feature_featured';
	protected $functionName = 'featured_listings';
	protected $parameters = array('featured_listings_template', 'number_of_rows', 'number_of_cols');

	public function respond()
	{
        $template = \App()->Request->getValueOrDefault('featured_listings_template', 'featured_listings.tpl');
        $number_of_rows = \App()->Request->getValueOrDefault('number_of_rows', 1);
        $number_of_cols = \App()->Request->getValueOrDefault('number_of_cols', 1);
        $number_of_listings = $number_of_rows * $number_of_cols;

        $featuredListingManager = new \modules\listing_feature_featured\lib\FeaturedListingManager();
        $template_processor = \App()->getTemplateProcessor();
        $template_processor->assign("listings", $featuredListingManager->getFeaturedListingsByUserSid($number_of_listings, \App()->Dealer['user_sid']));
        $template_processor->assign("number_of_rows", $number_of_rows);
        $template_processor->assign("number_of_cols", $number_of_cols);
        $template_processor->display($template);
	}
}
