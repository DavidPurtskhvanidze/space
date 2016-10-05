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


namespace modules\classifieds\lib;

class ExpireListingsProcessor
{
	private $userManager;
	private $listingManager;
	private $adminReporter;
	private $listingsSid;

	private $ObjectMother;
	public function setObjectMother($m){$this->ObjectMother = $m;}

	function setUserManager($userManager)
	{
		$this->userManager = $userManager;
	}
	function setListingManager($listingManager)
	{
		$this->listingManager = $listingManager;
	}
	function setAdminReporter($adminReporter)
	{
		$this->adminReporter = $adminReporter;
	}
	function setListingsSid($listingsSid)
	{
		$this->listingsSid = $listingsSid;
	}
	function perform()
	{
		$listingsSidSplittedByUsers = $this->splitListingsSidByUsers($this->listingsSid);
		foreach ($listingsSidSplittedByUsers as $userSid => $userListingsSid)
		{
			$user = $this->userManager->getObjectBySid($userSid);
			$logger = $this->ObjectMother->createExpiredUserListingLogger($user);
			$expireUserListingsAction = $this->ObjectMother->createExpireUserListingsAction($userListingsSid);
			$expireUserListingsAction->perform();
			
			$logger->setExpiredListingsSid($userListingsSid);
			
			$expiredListingsUserReporter = $this->ObjectMother->createExpiredListingsUserReporter($user, $logger);
			$expiredListingsUserReporter->perform();
			
			$this->adminReporter->addExpiredUserListingsLogger($logger);
		}
	}
	function splitListingsSidByUsers($listingsSid)
	{
		$res = array();
		foreach ($listingsSid as $listingSid)
		{
			$userSid = $this->listingManager->getUserSIDByListingSID($listingSid);
			$res[$userSid][] = $listingSid;
		}
		return $res;
	}
	
}

?>
