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

class ActivateOptionsAction
{
	private $listing;
	private $optionIds = array();

	public function perform()
	{
		foreach ($this->optionIds as $optionId)
		{
			if (\App()->AdditionalListingOptionManager->isListingOptionWithIdExist($this->listing, $optionId))
			{
				\App()->AdditionalListingOptionManager->activateOptionForListing($this->listing, $optionId);
			}
			else {
				\App()->ListingFeaturesManager->activateFeatureForListing($this->listing, $optionId);
			}
		}
	}

	public function setListing($listing)
	{
		$this->listing = $listing;
	}

	public function setOptionIds($optionIds)
	{
		$this->optionIds = $optionIds;
	}
}
