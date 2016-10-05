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


namespace modules\classifieds\lib\Actions;

class DisplayOptionsAppliedMessageAction
{
	private $redirectUrl;
	/**
	 * @var \modules\classifieds\apps\FrontEnd\scripts\ManageListingOptionsHandlerLogger
	 */
	private $logger;

	/**
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;

	public function perform()
	{
		$optionsInfo = array
		(
			'activation' => array
			(
				'id' => 'activation',
				'name' => 'Listing Activation',
			),
		);
		$listingFeatures = \App()->ListingFeaturesManager->getFeatures();
		array_walk($listingFeatures, function($feature) use (&$optionsInfo)
		{
			/**
			 * @var \modules\membership_plan\IListingFeature $feature
			 */
			$optionsInfo[$feature->getListingPropertyId()] = array
			(
				'id' => $feature->getListingPropertyId(),
				'name' => $feature->getFeatureName(),
			);
		});
		
		$additionalListingOptions = \App()->AdditionalListingOptionManager->getOptionTemplateStructureByListing($this->listing);
		foreach ($additionalListingOptions as $option)
		{
			$optionsInfo[$option['id']] = array
			(
				'id' => $option['id'],
				'name' => $option['caption'],
			);
		}

		if (count($this->logger->getOptionsActivated()) > 0)
		{
			$optionsActivatedInfo = array_intersect_key($optionsInfo, array_flip($this->logger->getOptionsActivated()));
			\App()->SuccessMessages->addMessage('OPTIONS_ACTIVATED', array('listingSid' => $this->listing->getSID(), 'options' => $optionsActivatedInfo, 'listingCaption' => (string) \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this->listing)));
		}
		if (count($this->logger->getOptionsAddedToContainer()) > 0)
		{
			$optionsAddedToContainerInfo = array_intersect_key($optionsInfo, array_flip($this->logger->getOptionsAddedToContainer()));
			\App()->WarningMessages->addMessage('OPTIONS_ADDED_TO_CONTAINER', array('listingSid' => $this->listing->getSID(), 'options' => $optionsAddedToContainerInfo, 'listingCaption' => (string) \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this->listing)));
		}
		if (count($this->logger->getOptionsAddedToBasket()) > 0)
		{
            $optionsAddedToBasketInfo = array_intersect_key($optionsInfo, array_flip($this->logger->getOptionsAddedToBasket()));
			\App()->WarningMessages->addMessage('OPTION_ADDED_TO_BASKET', array('listingSid' => $this->listing->getSID(), 'options' => $optionsAddedToBasketInfo, 'listingCaption' => (string) \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this->listing)));
		}
		
		throw new \lib\Http\RedirectException($this->redirectUrl);
	}

	public function setLogger($logger)
	{
		$this->logger = $logger;
	}

	public function setListing($listing)
	{
		$this->listing = $listing;
	}

	public function setRedirectUrl($redirectUrl)
	{
		$this->redirectUrl = $redirectUrl;
	}
}
