<?php
/**
 *
 *    Module: membership_plan v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: membership_plan-7.5.0-1
 *    Tag: tags/7.5.0-1@19798, 2016-06-17 13:20:05
 *
 *    This file is part of the 'membership_plan' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\membership_plan\lib; 

class NotifyListingExpirationApproachingTask extends \modules\miscellaneous\lib\ScheduledTaskBase
{
	public static function getOrder()
	{
		return 5000;
	}
	
	public function run()
	{
		$this->scheduler->log('Notifying owners on listing expiration date approaching');
		
		$expiresWithinDays = \App()->SettingsFromDB->getSettingByName('listing_and_subscription_notification_threshold');
		$fromDate = new \DateTime($this->scheduler->getLastRunTime());
		$fromDate->add(new \DateInterval('P'. ($expiresWithinDays + 1) . 'D'));
		$toDate = new \DateTime($this->scheduler->getStartTime());
		$toDate->add(new \DateInterval('P'. $expiresWithinDays . 'D'));
		
		$request = array();
		$request['active']['equal'] = 1;
		$request['expiration_date']['not_earlier_using_iso_date_time'] = $fromDate->format('Y-m-d H:i:s');
		$request['expiration_date']['not_later_using_iso_date_time'] = $toDate->format('Y-m-d H:i:s');
		
		$search = $this->getSearch();
		$search->setRequest($request);
		
		$this->scheduler->log(sprintf('Found %d listing expiration date approaching', $search->getNumberOfObjectsFound()));
		if ($search->getNumberOfObjectsFound() > 0)
		{
			$usernameGroupedListings = $this->getListingGroupedByUserenames($search);
			foreach($usernameGroupedListings as $userListings)
			{
				$this->scheduler->log('Sending email to ' . ((string) $userListings[0]['user']['email']));
				$this->sendNotificationLetter($userListings[0]['user'], $userListings, $expiresWithinDays);
			}
		}
	}
	
	private function sendNotificationLetter($user, $listings, $expiresWithinDays)
	{
		return \App()->EmailService->send(
			(string) $user['email'],
			'email_template:listing_expiration_approaching_notification',
			array(
				'user' => $user,
				'listings' => $listings,
				'numberOfDays' => $expiresWithinDays
			)
		);
	}

	private function getListingGroupedByUserenames($search)
	{		
		$listings = \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
		$result = array();
		foreach($listings as $listing)
		{
			$result[((string)$listing['user']['username'])][] = $listing;
		}
		return $result;
	}

	private function getSearch()
	{
		$search = new \lib\ORM\SearchEngine\Search();
		$search->setDB(\App()->DB);
		$search->setRowMapper(new \modules\classifieds\lib\ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
		$search->setModelObject(\App()->ListingFactory->getListing(array(), 0));
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		$search->setRequest(array());
		
		$search->setSortingFields(array());
		$search->setPage(1);
		$search->setObjectsPerPage(1000);
		
		return $search;
	}
}
