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

class ExpiredListingsUserReporter extends \modules\users\apps\FrontEnd\AbstractUserNotification
{
	var $user;
	var $logger;

	function setUser(&$user)
	{
		$this->user = $user;
	}
	function setLogger(&$logger)
	{
		$this->logger = $logger;
	}
	function perform()
	{
		if ($this->getValue($this->user->getSID()))
		{
			$data = array
			(
				'user' => \App()->UserManager->createTemplateStructureForUser($this->user),
				'expiredListings' => $this->getSearchObjectFromListingsSID($this->logger->getExpiredListingsSid()),
			);
			return \App()->EmailService->send($this->user->getPropertyValue('email'), 'email_template:expired_listings_report', $data);
		}
		return false;
	}

	private function getSearchObjectFromListingsSID($listingsSID)
	{
		if (!empty($listingsSID))
		{
			$search = new \lib\ORM\SearchEngine\Search();
			$search->setPage(1);
			$search->setObjectsPerPage(1000000);
			$search->setRequest(array('id' => array('in' => $listingsSID)));
			$search->setDB(\App()->DB);
			$search->setRowMapper(new \modules\classifieds\lib\ListingsFactoryToRowMapperAdapter(\App()->ListingFactory));
			$search->setModelObject(\App()->ListingFactory->getListing(array(), 0));
			$search->setCriterionFactory(\App()->SearchCriterionFactory);
			return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
		}
		else
		{
			return array();
		}
	}

	public function getId()
	{
		return 'listing_expiration';
	}

	public function getCaption()
	{
		return 'Notify on Listing Expiration';
	}

}
