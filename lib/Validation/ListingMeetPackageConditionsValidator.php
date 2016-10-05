<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\Validation;

class ListingMeetPackageConditionsValidator
{
	var $listing;
	var $userContract;
	var $listingGallery;
	var $listingFieldManager;
	var $errors = array();
    private $errorsData = array();

	function isValid($packageId)
	{
		// check pictures
		$this->listingGallery->setListingSID($this->listing->getID());
		$picturesAmount = $this->listingGallery->getPicturesAmount();
		$packageInfo = $this->userContract->getPackageInfoByPackageSID($packageId);
		if ((int) $picturesAmount > (int) $packageInfo['pic_limit'])
		{
			$this->errors[] = 'NUMBER_OF_PICTURES_MORE_THAN_ALLOWED';
            $this->errorsData['NUMBER_OF_PICTURES_MORE_THAN_ALLOWED'] = ['picture_number' => $picturesAmount - $packageInfo['pic_limit']];
		}
		// check video
		if (!$packageInfo['video_allowed'])
		{
			$videoFields = $this->listingFieldManager->getFieldsInfoByType("video");
			foreach ($videoFields as $fieldInfo)
			{
				$v = $this->listing->getPropertyValue($fieldInfo['id']);
				if (!empty($v['file_url']))
				{
					$this->errors[] = 'VIDEO_IS_NOT_ALLOWED';
					break;
				}
				
			}
		}
		return empty($this->errors);
	}

	function setListingFieldManager($listingFieldManager)
	{
		$this->listingFieldManager = $listingFieldManager;
	}
	function setListingGallery($listingGallery)
	{
		$this->listingGallery = $listingGallery;
	}
	function setUserContract($userContract)
	{
		$this->userContract = $userContract;
	}
	function setListing($listing)
	{
		$this->listing = $listing;
	}
	function getErrors()
	{
		return $this->errors;
	}

    /**
     * @return array
     */
    public function getErrorsData()
    {
        return $this->errorsData;
    }
}

?>
