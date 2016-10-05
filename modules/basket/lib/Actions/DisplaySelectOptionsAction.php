<?php
/**
 *
 *    Module: basket v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: basket-7.5.0-1
 *    Tag: tags/7.5.0-1@19771, 2016-06-17 13:18:56
 *
 *    This file is part of the 'basket' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\basket\lib\Actions;

class DisplaySelectOptionsAction
{
	/**
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;

	private $predefinedRequestData = array();

	public function perform()
	{
		$freeFeatures = \App()->ListingFeaturesManager->getFreeFeaturesByPackageInfo($this->listing->getListingPackageInfo());
		$paidFeatures = \App()->ListingFeaturesManager->getPaidFeaturesByPackageInfo($this->listing->getListingPackageInfo());
		$additionalListingOptions = \App()->AdditionalListingOptionManager->getOptionTemplateStructureByListing($this->listing);

		$alreadyActivatedFeatures = array();
		foreach ($freeFeatures as $featureId => $feature)
		{
			if ((bool)$this->listing->getPropertyValue($featureId))
			{
				$alreadyActivatedFeatures[$featureId] = $feature;
				unset($freeFeatures[$featureId]);
			}
		}
		foreach ($paidFeatures as $featureId => $feature)
		{
			if ((bool)$this->listing->getPropertyValue($featureId))
			{
				$alreadyActivatedFeatures[$featureId] = $feature;
				unset($paidFeatures[$featureId]);
			}
		}

		$templateProcessor = \App()->getTemplateProcessor();
		$templateProcessor->assign('alreadyActivatedFeatures', $alreadyActivatedFeatures);
		$templateProcessor->assign('availableFreeFeatures', $freeFeatures);
		$templateProcessor->assign('availablePaidFeatures', $paidFeatures);
		$templateProcessor->assign('additionalListingOptions', $additionalListingOptions);
		$templateProcessor->assign('predefinedRequestData', $this->predefinedRequestData);
		$templateProcessor->assign('listingIsActive', $this->listing->isActive());
		$templateProcessor->display("classifieds^select_listing_options.tpl");
	}

	public function setListing($listing)
	{
		$this->listing = $listing;
	}

	public function setPredefinedRequestData($predefinedRequestData)
	{
		$this->predefinedRequestData = $predefinedRequestData;
	}
}
