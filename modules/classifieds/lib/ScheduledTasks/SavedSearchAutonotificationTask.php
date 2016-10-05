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

class SavedSearchAutonotificationTask extends \modules\miscellaneous\lib\ScheduledTaskBase
{
	public static function getOrder()
	{
		return 300;
	}
	public function run()
	{
		$this->scheduler->log('Starting saved search notifications');
		$savedSearchesInfo = \App()->SavedSearchManager->getAutoNotifySavedSearches();
		$this->scheduler->log('Found ' . count($savedSearchesInfo) . ' saved searches');
		foreach ($savedSearchesInfo as $searchInfo)
		{
			$search = unserialize($searchInfo['data']);
			$requestData = $search->getRequest();
			$requestData['active']['equal'] = 1;
			$requestData['activation_date']['not_earlier_using_iso_date_time'] = $this->scheduler->getLastRunTime();
			$requestData['activation_date']['not_later_using_iso_date_time'] = $this->scheduler->getStartTime();
			$this->categorySid = isset($requestData['category_sid']['tree']) ? current($requestData['category_sid']['tree']) : 0;
			$search->setRequest($requestData);
			$search->setDB(\App()->DB);
			$search->setRowMapper(new \modules\classifieds\lib\ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
			$search->setModelObject($this->getModelListing());
			$search->setCriterionFactory(\App()->SearchCriterionFactory);
			$search->setSortingFields(array());
			$numberOfNewListings = $search->getNumberOfObjectsFound();
			if ($numberOfNewListings > 0)
			{
				$search->setObjectsPerPage($numberOfNewListings);
				$search->setPage(1);
				$this->sendUserNewListingsFoundLetter($search, $searchInfo['user_sid'], $searchInfo);
			}
		}
	}

	private function sendUserNewListingsFoundLetter($search, $user_sid, $saved_search_info)
	{
		$userInfo = \App()->UserManager->getUserInfoBySID($user_sid);
		$this->scheduler->log("Sending email to {$userInfo['email']}");
		return \App()->EmailService->send($userInfo['email'], 'email_template:saved_search_notification', array(
			'listings' => $this->getListings($search),
			'user' => $userInfo,
			'saved_search' => $saved_search_info
		));
	}
	
	private function getListings($search)
	{
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
	}
	
	private $modelListing = null;
	private function getModelListing()
	{
		if (is_null($this->modelListing)) $this->modelListing = \App()->ListingFactory->getListing(array(), $this->categorySid);
		return $this->modelListing;
	}

}
