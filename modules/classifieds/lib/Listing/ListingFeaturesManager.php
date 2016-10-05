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


namespace modules\classifieds\lib\Listing;

class ListingFeaturesManager implements \core\IService
{
	/**
	 * @var \modules\membership_plan\IListingFeature[]
	 */
	private $features = array();

	/**
	 * @var \modules\membership_plan\IListingFeature[]
	 */
	private $featuresById = array();
	private $featuresDetails;
	private $featureListingDetails;

	public function init()
	{
		$features = new \core\ExtensionPoint('modules\membership_plan\IListingFeature');
		foreach ($features as $feature)
		{
			/**
			 * @var \modules\membership_plan\IListingFeature $feature
			 */
			$this->features[$feature->getFeatureName()] = $feature;
			$this->featuresById[$feature->getListingPropertyId()] = $feature;
		}
	}

	public function getFeatures()
	{
		return $this->features;
	}

	public function getAllFeatureIds()
	{
		return array_keys($this->featuresById);
	}
	
	public function getFreeFeatureIdsByPackageInfo($packageInfo)
	{
		return array_keys($this->getFreeFeaturesByPackageInfo($packageInfo));
	}

	public function getPaidFeatureIdsByPackageInfo($packageInfo)
	{
		return array_keys($this->getPaidFeaturesByPackageInfo($packageInfo));
	}

	public function getFreeFeaturesByPackageInfo($packageInfo)
	{
		$freeFeatures = array();
		foreach ($this->features as $feature)
		{
			if (!$this->isFeatureAllowed($packageInfo, $feature))
			{
				continue;
			}
			if ($packageInfo[$feature->getPricePropertyId()] == 0)
			{
				$freeFeatures[$feature->getListingPropertyId()] = array
				(
					'id' => $feature->getListingPropertyId(),
					'name' => $feature->getFeatureName(),
					'caption' => $feature->getListingPropertyCaption()
				);
			}
		}
		return $freeFeatures;
	}

	public function getPaidFeaturesByPackageInfo($packageInfo)
	{
		$paidFeatures = array();
		foreach ($this->features as $feature)
		{
			if (!$this->isFeatureAllowed($packageInfo, $feature))
			{
				continue;
			}
			if ($packageInfo[$feature->getPricePropertyId()] > 0)
			{
				$paidFeatures[$feature->getListingPropertyId()] = array
				(
					'id' => $feature->getListingPropertyId(),
					'name' => $feature->getFeatureName(),
					'caption' => $feature->getListingPropertyCaption(),
					'price' => $packageInfo[$feature->getPricePropertyId()]
				);
			}
		}
		return $paidFeatures;
	}

	/**
	 * @param Listing $listing
	 * @param string $featureId
	 */
	public function activateFeatureForListing($listing, $featureId)
	{
		// need to clone $listing to safely operate with it
		$tmpListing = clone $listing;
		$tmpListing->setPropertyValue($this->featuresById[$featureId]->getListingPropertyId(), true);

		// delete all properties except property for activated feature
		$propertyIds = array_keys($tmpListing->getDetails()->getProperties());
		$propertiesToExclude = array_diff($propertyIds, array($this->featuresById[$featureId]->getListingPropertyId()));
		array_walk($propertiesToExclude, array($tmpListing, 'deleteProperty'));

		\App()->ListingManager->saveListing($tmpListing);
		$tmpListing = null;

		/**
		 * @var \modules\classifieds\IAfterListingFeatureActivated[] $afterListingFeatureActivatedActions
		 */
		$afterListingFeatureActivatedActions = new \core\ExtensionPoint('modules\classifieds\IAfterListingFeatureActivated');
		foreach ($afterListingFeatureActivatedActions as $afterListingFeatureActivatedAction)
		{
			$afterListingFeatureActivatedAction->setFeatureId($featureId);
			$afterListingFeatureActivatedAction->setListing($listing);
			$afterListingFeatureActivatedAction->perform();
		}
	}

	/**
	 * @param Listing $listing
	 * @param $featureId
	 */
	public function deactivateFeatureForListing($listing, $featureId)
	{
		$listing->setPropertyValue($this->featuresById[$featureId]->getListingPropertyId(), false);
		$listing->deleteProperty('activation_date');

		\App()->ListingManager->saveListing($listing);

		/**
		 * @var \modules\classifieds\IAfterListingFeatureDeactivated[] $afterListingFeatureDeactivatedActions
		 */
		$afterListingFeatureDeactivatedActions = new \core\ExtensionPoint('modules\classifieds\IAfterListingFeatureDeactivated');
		foreach ($afterListingFeatureDeactivatedActions as $afterListingFeatureDeactivatedAction)
		{
			$afterListingFeatureDeactivatedAction->setFeatureId($featureId);
			$afterListingFeatureDeactivatedAction->setListing($listing);
			$afterListingFeatureDeactivatedAction->perform();
		}
	}

	/**
	 * @param Listing $listing
	 * @param string $featureId
	 * @return float
	 */
	public function getPriceForFeature($listing, $featureId)
	{
		if (empty($listing))
		{
			return null;
		}
		$packageInfo = $listing->getListingPackageInfo();
		return $packageInfo[$this->featuresById[$featureId]->getPricePropertyId()];
	}

	public function disableModifyingListingFeatures($listing)
	{
		$listingFeaturesDetails = $this->getListingFeaturesDetails();
		$listingFeaturesDetailsIds = array_map(create_function('$detail', 'return isset($detail["id"]) ? $detail["id"] : null;'), $listingFeaturesDetails);
		array_walk($listingFeaturesDetailsIds, array(&$listing, 'deleteProperty'));
	}

	public function deactivateAllFeatures($listing)
	{
		foreach ($this->featuresById as $featureId => $feature)
		{
			$this->deactivateFeatureForListing($listing, $featureId);
		}
	}

	/**
	 * @param Listing $listing
	 * @param string $featureName
	 */
	public function setListingFeatureOn($listing, $featureName)
	{
		$listing->setPropertyValue($this->features[$featureName]->getListingPropertyId(), true);
	}

	public function getListingFeaturesDetails()
	{
		if (is_null($this->featuresDetails))
		{
			$this->defineListingFeaturesDetails();
		}
		return $this->featuresDetails;
	}

	private function defineListingFeaturesDetails()
	{
		$this->featuresDetails = array();
		foreach ($this->features as $featureName => $feature)
		{
			$this->featuresDetails[$featureName] = array
			(
				'id'			=> $feature->getListingPropertyId(),
				'caption'		=> $feature->getListingPropertyCaption(),
				'type'			=> 'boolean',
				'length'		=> '20',
				'is_required'	=> false,
				'is_system'		=> false,
				'order'			=> null,
			);
		}
	}
	
	public function getFeatureListingDetails()
	{
		if (is_null($this->featureListingDetails))
		{
			$this->defineFeatureListingDetails();
		}
		return $this->featureListingDetails;
	}

	private function defineFeatureListingDetails()
	{
		$this->featureListingDetails = array();
		/**
		 * @var \modules\membership_plan\IListingFeatureListingDetails[] $featureListingDetails
		 */
		$featureListingDetails = new \core\ExtensionPoint('modules\membership_plan\IListingFeatureListingDetails');
		foreach ($featureListingDetails as $listingDetails)
		{
			$this->featureListingDetails += $listingDetails->getListingDetails();
		}
	}

	/**
	 * @param array $packageInfo
	 * @param \modules\membership_plan\IListingFeature $feature
	 * @return boolean
	 */
	private function isFeatureAllowed($packageInfo, $feature)
	{
		/**
		 * For backward compatibility with version 6.3.0
		 *
		 * If package does not contain info about the feature allowed
		 * then this feature will be allowed. This is done as the packages saved
		 * in DB of the version 6.3.0 do not contain such info.
		 */
		if (!isset($packageInfo[$feature->getAllowedPropertyId()]))
		{
			$packageInfo[$feature->getAllowedPropertyId()] = true;
		}
		return $packageInfo[$feature->getAllowedPropertyId()];
	}
}
