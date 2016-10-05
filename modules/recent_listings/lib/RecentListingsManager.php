<?php
/**
 *
 *    Module: recent_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: recent_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19807, 2016-06-17 13:20:30
 *
 *    This file is part of the 'recent_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\recent_listings\lib;

class RecentListingsManager
{
	public function getRecentListings($numberOfListings, $category_id = 'root', $userSid = null)
	{
		$categoryBranchesSid = \App()->CategoryManager->getBranchesSids($category_id);
		$modelListing = \App()->ListingFactory->getListing([], 0);
		$modelListing->addFirstActivationDateProperty();
		$criteria = ['active' => ['equal' => 1], 'category_sid' => ['in' => $categoryBranchesSid]];
		if ($userSid)
		{
			$criteria['user_sid'] = ['equal' => $userSid];
		}

		$search = \App()->ListingManager->getSearch($criteria);
		$search->setModelObject($modelListing);
		$search->setSortingFields(['first_activation_date' => 'DESC']);
		$search->setObjectsPerPage($numberOfListings);
		$search->setPage(1);
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
	}
}
