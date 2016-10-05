<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\apps\FrontEnd\scripts;

class QuickViewListingHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Quick View Listing';
	protected $moduleName = 'classifieds';
	protected $functionName = 'quick_view_listing';
	protected $parameters = array('display_template');
	protected $rawOutput = true;

	public function respond()
	{
		$template_processor = \App()->getTemplateProcessor();
		$displayHelper = \modules\classifieds\lib\Listing\DisplayHelper::getInstance(\App()->Request->getRequest());

		if ($displayHelper->canDisplay())
		{
			$listing = $displayHelper->getListing();
			$addListingProperties = new \core\ExtensionPoint('modules\classifieds\apps\FrontEnd\IAddListingPropertyOnDisplayListing');
			foreach ($addListingProperties as $addListingProperty)
			{
				$addListingProperty->setListing($listing);
				$addListingProperty->perform();
			}

			\App()->ListingManager->incrementViewsCounterForListing($displayHelper->getListingId());

			$display_form = new \lib\Forms\Form($listing);
			$display_form->registerTags($template_processor);
			$template_processor->assign("form_fields", $display_form->getFormFieldsInfo());

			$magicFields = new \modules\classifieds\lib\MagicFields($display_form->getFormFieldsInfo());

			$template_processor->assign("magicFields", $magicFields);

			$listingsIdsInComparison = \App()->ObjectMother->createListingComparisonTable()->getListings();

			$listingDisplayer = new \modules\classifieds\lib\Listing\ListingDisplayer();
			$listingDisplayer->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);
			$listingDisplayer->setSavedListingsIds(\App()->ObjectMother->createSavedListings()->getSavedListings());
			$listingDisplayer->setListingsIdsInComparison($listingsIdsInComparison);

			$template_processor->assign("listing", $listingDisplayer->wrapListing($listing));
			$template = !empty($_REQUEST['display_template']) ? $_REQUEST['display_template'] : 'category_templates/display/default_quick_view.tpl';
			$template_processor->display($template);
		}
		else
		{
			$template_processor->assign("errors", $displayHelper->getErrors());
			$template_processor->display('category_templates/display/display_errors.tpl');
		}
	}
}
