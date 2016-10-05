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

class DeactivateExpiredListingsTask extends \modules\miscellaneous\lib\ScheduledTaskBase
{
	public static function getOrder()
	{
		return 200;
	}
	public function run()
	{
		$this->scheduler->log('Expiring listings');
		$expiredListingsSid = \App()->ListingManager->getListingsSidExpiredBeetwen($this->scheduler->getLastRunTime(), $this->scheduler->getStartTime());
		$this->scheduler->log(sprintf('Found %d expired listings. %s', count($expiredListingsSid), join(', ', $expiredListingsSid)));
		\App()->ObjectMother->createExpireListingsProcessor($expiredListingsSid)->perform();
		\App()->ObjectMother->getAdminReporter()->report();
	}
}
