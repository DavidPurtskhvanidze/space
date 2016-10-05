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


namespace modules\classifieds\lib\ScheduledTasks;

use modules\miscellaneous\lib\ScheduledTaskBase;
use modules\miscellaneous\lib\TimeCalculator;

class UpdateListingsKeywordsTask extends ScheduledTaskBase
{
	public static function getOrder()
	{
		return 400;
	}
	public function run()
	{
		$this->scheduler->log('Starting Listings Keywords Updating');
		$listingsIds = \App()->ListingManager->getListingsIdsForKeywordsUpdating();
		\App()->ListingManager->setListingsIdsForKeywordsUpdating(array());
		$this->scheduler->log(sprintf('Found %d listings for keyword updating. %s', count($listingsIds), join(', ', $listingsIds)));
        if (empty($listingsIds)) return false;
		$listingsIds = $this->filterExisting($listingsIds);
		
		$timeLimit = \App()->SettingsFromDB->getSettingByName('max_time_to_execute_update_listings_keywords');
		$timeLimitInMicroseconds = 1000 * $timeLimit;
		$timer = new TimeCalculator('Timer for UpdateListingsKeywordsTask');
		$updatedListingsIds = array();
		foreach ($listingsIds as $listingId)
		{
			\App()->ListingManager->updateListingKeywords($listingId);
			$updatedListingsIds[] = $listingId;
			if ($timer->getElapsedTime() >= $timeLimitInMicroseconds) break;
		}
		$this->scheduler->log(sprintf('%d listings keywords were updated in %d seconds. %s', count($updatedListingsIds), $timeLimit, join(', ', $updatedListingsIds)));
		$notUpdatedListingsIds = array_diff($listingsIds, $updatedListingsIds);
		\App()->ListingManager->addListingsIdsForKeywordsUpdating($notUpdatedListingsIds);
	}
	private function filterExisting($listingsIds)
	{
		$allLisingsIds = \App()->ListingManager->getAllListingSIDs();
		$allLisingsIds = array_map(create_function('$d', 'return $d["sid"];'), $allLisingsIds);
		return array_intersect($listingsIds, $allLisingsIds);
	}
}
