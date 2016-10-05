<?php
/**
 *
 *    Module: listing_option_reactivation v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_option_reactivation-7.5.0-1
 *    Tag: tags/7.5.0-1@19794, 2016-06-17 13:19:54
 *
 *    This file is part of the 'listing_option_reactivation' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_option_reactivation\lib;

class ListingReactivationTask extends \modules\miscellaneous\lib\ScheduledTaskBase
{
	/**
	 * Activation option id
	 * @var String
	 */
	private $activationOptionId = 'activation';
	/**
	 * ListingFilesManager
	 * @var \lib\ORM\ObjectFilesManager
	 */
	private $listingFilesManager;
	/**
	 * Listing Gallery
	 * @var \modules\classifieds\lib\ListingGallery\ListingGallery
	 */
	private $listingGallery;
	
	public static function getOrder()
	{
		return 100;
	}
	
	private function init()
	{
		$this->listingFilesManager = \App()->ObjectMother->createListingFilesManager();
		$this->listingGallery = \App()->ListingGalleryManager->createListingGallery();
	}
	
	private function activateListingFeatures(ListingReactivation $reactivation)
	{
		$optionIds = $reactivation->getOptionsToActivate();
		foreach($optionIds as $optionId)
		{
			if ($optionId !== $this->activationOptionId)
			{
				$action = \App()->ObjectMother->createActivateListingFeatureAction($reactivation->getListingSid(), $optionId);
				$action->perform();
			}
		}
	}

	private function activateListing(ListingReactivation $reactivation)
	{
		$action = \App()->ObjectMother->createActivateListingAction($reactivation->getListingSid());
		$action->perform();
	}
	
	private function fixListingDataAccordingToPackage(ListingReactivation $reactivation)
	{
		$listingPackageInfo = $reactivation->getPackageInfo();
		$listing = \App()->ListingManager->getListingBySid($reactivation->getListingSid());

		// Setting new package
		$listing->setListingPackageInfo($listingPackageInfo);
		\App()->ListingPackageManager->updatePackage($listing->getSID(), $listingPackageInfo);
		
		// Removing video file if new package does not allow video
		if (!((bool) $listingPackageInfo['video_allowed']))
		{
			$this->listingFilesManager->deleteFileByProperty($listing->getProperty('Video'));
			$listing->setPropertyValue('Video', null);
		}

		\App()->ListingManager->saveListing($listing);
		
		// Removing outfitting images
		$this->listingGallery->setListingSID($reactivation->getListingSid());
		$this->listingGallery->setListing($listing);

		$pictureDiff = $this->listingGallery->getPicturesAmount() - $listingPackageInfo['pic_limit'];
		if ($pictureDiff > 0)
		{
			$pictureInfo = $listing->getProperty('pictures')->type->getDisplayValue()['collection'];
			usort($pictureInfo, function($a, $b){return ($a['id'] < $b['id']) ? -1 : 1;});
			while($pictureDiff > 0)
			{
				$lastPictureInfo = array_pop($pictureInfo);
				$this->listingGallery->deleteImageBySID($lastPictureInfo['id']);
				$pictureDiff--;
			}
		}
	}
	
	private function getReactivationsWithListingsGroupedByListingSid()
	{
		$result = array();
		$reactivations = \App()->ListingReactivationManager->getActiveReactivations();
		foreach($reactivations as $reactivation)
		{
			$listing = \App()->ListingManager->getListingBySid($reactivation->getListingSid());
			if (!is_null($listing) && $listing->isExpired() && $listing->getModerationStatus() == 'APPROVED')
			{
				$result[$reactivation->getListingSid()] = $reactivation;
			}
		}
		return $result;
	}
	
	private function removeUnavailableFeatures(ListingReactivation $reactivation)
	{
		$reservedOptions = array('activation');
		
		$optionsToActivate = $reactivation->getOptionsToActivate();
		$availableOptions = \App()->ListingFeaturesManager->getAllFeatureIds();
		$missingOptions = array();
		foreach ($optionsToActivate as $optionIndex => $option)
		{
			if (false !== array_search($option, $reservedOptions))
			{
				continue;
			}
			if (false === array_search($option, $availableOptions))
			{
				$missingOptions[] = $option;
				unset($optionsToActivate[$optionIndex]);
			}
		}
		
		if (!empty($missingOptions))
		{
			$reactivation->setOptionsToActivate($optionsToActivate);
			$this->scheduler->log(sprintf('Warning: option(s) "%s" not found.', implode('", "', $missingOptions)));
		}
	}
	
	public function run()
	{
		$this->scheduler->log('Reactivating listings');
		
		$this->init();
		$reactivations = $this->getReactivationsWithListingsGroupedByListingSid();

		$this->scheduler->log(sprintf('Found %d listing reactivations. %s' , count($reactivations), implode(',', array_keys($reactivations))));
		
		$expireUserListingsAction = \App()->ObjectMother->createExpireUserListingsAction(array_keys($reactivations));
		$expireUserListingsAction->perform();
		
		foreach($reactivations as $reactivation)
		{
			$this->scheduler->log(sprintf('Reactivating listing %s' , $reactivation->getListingSid()));
			$this->fixListingDataAccordingToPackage($reactivation);
			$this->removeUnavailableFeatures($reactivation);
			$this->activateListingFeatures($reactivation);
			$this->activateListing($reactivation);
			\App()->ListingReactivationManager->deleteListingReactivationBySid($reactivation->getSID());
		}
	}
}
