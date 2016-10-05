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

class ManageListingOptionsHandlerContext
{
	/**
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;

	/**
	 * @var \modules\membership_plan\lib\Contract\Contract
	 */
	private $userContract;

	public function isActivationFree()
	{
		$package_info = $this->listing->getListingPackageInfo();
		return $package_info['price'] == 0;
	}

	public function getFreeOptionIdsToAddToContainer()
	{
		$freeFeaturesIds = array_merge(
			\App()->ListingFeaturesManager->getFreeFeatureIdsByPackageInfo($this->listing->getListingPackageInfo()),
			\App()->AdditionalListingOptionManager->getFreeOptionIdsByListing($this->listing)
		);
		return array_intersect($freeFeaturesIds, $this->getSelectedOptionIds());
	}

	public function getPaidOptionIdsToAddToBasket()
	{
		$paidFeaturesIds = array_merge(
			\App()->ListingFeaturesManager->getPaidFeatureIdsByPackageInfo($this->listing->getListingPackageInfo()),
			\App()->AdditionalListingOptionManager->getPaidOptionIdsByListing($this->listing)
		);

		return array_intersect($paidFeaturesIds, $this->getSelectedOptionIds());
	}

	public function getFreeOptionIdsToActivate()
	{
		$freeFeaturesIds = array_merge(
			\App()->ListingFeaturesManager->getFreeFeatureIdsByPackageInfo($this->listing->getListingPackageInfo()),
			\App()->AdditionalListingOptionManager->getFreeOptionIdsByListing($this->listing)
		);
		return array_intersect($freeFeaturesIds, $this->getSelectedOptionIds());
	}

	private function getSelectedOptionIds()
	{
		$selectedOptionIds = \App()->Request['selectedOptionIds'];
		return is_array($selectedOptionIds)? $selectedOptionIds: array();
	}

	public function areOptionsSelected()
	{
		return \App()->Request['listing_options_selected'] == 1;
	}

	public function getListing()
	{
		return $this->listing;
	}

	public function getListingSid()
	{
		return $this->listing->getSID();
	}

	public function getListingOwnerSid()
	{
		return $this->listing->getUserSID();
	}

	public function isListingActive()
	{
		return $this->listing->isActive();
	}

	public function isListingNeverActivatedBefore()
	{
		return $this->listing->isNeverActivatedBefore();
	}

	public function isListingExpired()
	{
		return $this->listing->isExpired();
	}

	public function setListing($listing)
	{
		$this->listing = $listing;
	}

	public function hasUserActiveContract()
	{
		$contract = $this->getUserContract();
		return !is_null($contract) && !$contract->isExpired();
	}

	public function isPackageChosen()
	{
		return \App()->Request['package_chosen'] == 1;
	}

	public function getChosenPackageSid()
	{
		return \App()->Request['package_sid'];
	}

	public function getUserContract()
	{
		return $this->userContract;
	}

	/**
	 * @param \modules\membership_plan\lib\Contract\Contract $userContract
	 */
	public function setUserContract($userContract)
	{
		$this->userContract = $userContract;
	}

	public function getPredefinedRequestData()
	{
		$data = array
		(
			'listing_sid' => $this->listing->getSID(),
		);
		$requestDataKeys = array('package_chosen', 'listing_options_selected', 'package_sid');
		foreach ($requestDataKeys as $key)
		{
			if (!is_null(\App()->Request[$key]))
			{
				$data[$key] = \App()->Request[$key];
			}
		}
		return $data;
	}
}
