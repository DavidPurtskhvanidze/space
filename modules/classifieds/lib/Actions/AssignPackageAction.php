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

class AssignPackageAction
{
	private $chosenPackageSid;

	/**
	 * @var \modules\classifieds\lib\Listing\Listing
	 */
	private $listing;

	/**
	 * @var \modules\membership_plan\lib\Contract\Contract
	 */
	private $contract;

	public function perform()
	{
		if (!$this->contract->isListingPackageAvailableBySID($this->chosenPackageSid))
		{
			throw new \modules\classifieds\lib\Exception("The chosen listing package is not available in your contract");
		}
		$listingMeetPackageConditionsValidator = \App()->ObjectMother->createListingMeetPackageConditionsValidator($this->listing, $this->contract);
		if (!$listingMeetPackageConditionsValidator->isValid($this->chosenPackageSid))
		{
			throw new \modules\classifieds\lib\Exception("Listing does not comply with chosen package conditions");
		}
		$packageInfo = $this->contract->getPackageInfoByPackageSID($this->chosenPackageSid);
		\App()->ListingPackageManager->updatePackage($this->listing->getID(), $packageInfo);
		$this->listing->setListingPackageInfo($packageInfo);
	}

	public function setChosenPackageSid($chosenPackageSid)
	{
		$this->chosenPackageSid = $chosenPackageSid;
	}

	public function setListing($listing)
	{
		$this->listing = $listing;
	}

	public function setContract($contract)
	{
		$this->contract = $contract;
	}
}
