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

use core\ExtensionPoint;

class ListingEraser
{
    /**
     * @var Listing
     */
    private $listing;
	private $listingSid;
	private $listingGallery;
    private $listingPackageManager;
	private $listingFilesManager;
	private $calendarManager;
	private $ratingManager;
	private $listingManager;


	public function perform()
	{
		$beforeListingDeleteActions = new ExtensionPoint('modules\classifieds\IBeforeDeleteListingsAction');
		foreach ($beforeListingDeleteActions as $afterListingDeleteAction)
		{
			$afterListingDeleteAction->setListingSid($this->listingSid);
			$afterListingDeleteAction->perform();
		}

		$this->listingFilesManager->deleteFiles($this->listingSid);
		$this->listingGallery->setListingSID($this->listingSid);
        $this->listingGallery->setListing($this->listing);
		$this->listingGallery->deleteImages();
		$this->listingPackageManager->deleteListingPackageByListingSID($this->listingSid);
		$this->calendarManager->deleteCalendarByListingSID($this->listingSid);
		$this->ratingManager->deleteRatingByObjectSID($this->listingSid);
		$this->listingManager->deleteListingBySID($this->listingSid);

		$beforeListingDeleteActions = new ExtensionPoint('modules\classifieds\IAfterListingDelete');
		foreach ($beforeListingDeleteActions as $afterListingDeleteAction)
		{
			$afterListingDeleteAction->setListingSid($this->listingSid);
			$afterListingDeleteAction->perform();
		}
	}

    /**
     * @param Listing $listing
     * @return ListingEraser
     */
    public function setListing($listing)
    {
        $this->listing = $listing;
        return $this;
    }

    /**
     * @param mixed $listingSid
     * @return ListingEraser
     */
    public function setListingSid($listingSid)
    {
        $this->listingSid = $listingSid;
        return $this;
    }

    /**
     * @param mixed $listingGallery
     * @return ListingEraser
     */
    public function setListingGallery($listingGallery)
    {
        $this->listingGallery = $listingGallery;
        return $this;
    }

    /**
     * @param mixed $listingPackageManager
     * @return ListingEraser
     */
    public function setListingPackageManager($listingPackageManager)
    {
        $this->listingPackageManager = $listingPackageManager;
        return $this;
    }

    /**
     * @param mixed $listingFilesManager
     * @return ListingEraser
     */
    public function setListingFilesManager($listingFilesManager)
    {
        $this->listingFilesManager = $listingFilesManager;
        return $this;
    }

    /**
     * @param mixed $calendarManager
     * @return ListingEraser
     */
    public function setCalendarManager($calendarManager)
    {
        $this->calendarManager = $calendarManager;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCalendarManager()
    {
        return $this->calendarManager;
    }

    /**
     * @param mixed $ratingManager
     * @return ListingEraser
     */
    public function setRatingManager($ratingManager)
    {
        $this->ratingManager = $ratingManager;
        return $this;
    }

    /**
     * @param mixed $listingManager
     * @return ListingEraser
     */
    public function setListingManager($listingManager)
    {
        $this->listingManager = $listingManager;
        return $this;
    }
}
