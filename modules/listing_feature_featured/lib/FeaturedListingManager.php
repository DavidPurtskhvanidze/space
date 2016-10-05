<?php
/**
 *
 *    Module: listing_feature_featured v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_feature_featured-7.5.0-1
 *    Tag: tags/7.5.0-1@19791, 2016-06-17 13:19:46
 *
 *    This file is part of the 'listing_feature_featured' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_feature_featured\lib;

class FeaturedListingManager
{
	public function getFeaturedListings($numberOfListings, $categoryId = 'root')
	{
		$categoryBranchesSid = \App()->CategoryManager->getBranchesSids($categoryId);
		$modelListing = \App()->ListingFactory->getListing([], 0);
		$modelListing->addProperty(
			[
				'id' => 'feature_last_showed',
				'type' => 'integer',
				'table_name' => 'classifieds_feature_display_rotator',
				'column_name' => 'order',
				'join_condition' =>
					[
					  ['key_column' => 'sid', 'foriegn_column' => 'listing_sid'],
					  ['foriegn_column' => 'feature_type', 'value' => 'Featured']
					],
			]);

		$search = \App()->ListingManager->getSearch(['feature_featured' => ['equal' => 1], 'active' => ['equal' => 1], 'category_sid' => ['in' => $categoryBranchesSid]]);

		$search->setModelObject($modelListing);
		$search->setSortingFields(['feature_last_showed' => 'ASC']);
		$search->setObjectsPerPage($numberOfListings);
		$search->setPage(1);

		$listings = $search->getFoundObjectCollection();
		$timestamp = time();
		foreach ($listings as $listing)
		{
			$listingSid = $listing->getSid();
			\App()->DB->query("DELETE FROM `classifieds_feature_display_rotator` WHERE `feature_type` = 'Featured' AND `listing_sid` = ?n", $listingSid);
			\App()->DB->query("INSERT INTO `classifieds_feature_display_rotator` SET `order` = ?n, `feature_type` = 'Featured', `listing_sid` = ?n", $timestamp, $listingSid);
			$timestamp++;
		}
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($listings);
	}

    public function getFeaturedListingsByUserSid($numberOfListings, $userSid)
    {
        $modelListing = \App()->ListingFactory->getListing(array(), 0);
        $modelListing->addProperty(
		[
            'id' => 'feature_last_showed',
            'type' => 'integer',
            'table_name' => 'classifieds_feature_display_rotator',
            'column_name' => 'order',
            'join_condition' =>
			[
                ['key_column' => 'sid', 'foriegn_column' => 'listing_sid'],
                ['foriegn_column' => 'feature_type', 'value' => 'Featured']
            ],
        ]);

        $search = \App()->ListingManager->getSearch(
			[
				'feature_featured' => ['equal' => 1],
				'active' => ['equal' => 1],
				'user_sid' => ['equal' => $userSid]
			]);

        $search->setModelObject($modelListing);
		$search->setSortingFields(['feature_last_showed' => 'ASC']);
		$search->setObjectsPerPage($numberOfListings);
		$search->setPage(1);

		$featuredListingSids = $search->getFoundObjectSidCollection($numberOfListings);
		$timestamp = time();
		foreach ($featuredListingSids as $listingSid)
        {
            \App()->DB->query("DELETE FROM `classifieds_feature_display_rotator` WHERE `feature_type` = 'Featured' AND `listing_sid` = ?n", $listingSid);
            \App()->DB->query("INSERT INTO `classifieds_feature_display_rotator` SET `order` = ?n, `feature_type` = 'Featured', `listing_sid` = ?n", $timestamp, $listingSid);
            $timestamp++;
        }
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($search->getFoundObjectCollection());
    }

	public function getAllFeatureListingSids()
	{
		$featuredListingsInfo = \App()->DB->query(
			"SELECT sid FROM `classifieds_listings` as l LEFT JOIN `classifieds_feature_display_rotator` as r ON l.sid = r.listing_sid
			WHERE l.`feature_featured` = 1 AND l.`active` = 1
			ORDER BY r.`order`"
		);
		$listingSids = [];
		foreach ($featuredListingsInfo as $listingInfo)
		{
			$listingSids[] = $listingInfo['sid'];
		}
		
		return $listingSids;
	}
	
	public function getFeatureListingBySids(array $sids)
	{
		$featuredListingsInfo = \App()->DB->query("SELECT * FROM `classifieds_listings` WHERE `feature_featured` = 1 AND `active` = 1 AND `sid` IN (?l)", $sids);
		$listings = [];
		foreach ($featuredListingsInfo as $listingInfo)
		{
			$listing = \App()->ListingManager->getObjectBySID($listingInfo['sid']);
			$listing->addProperty(
				[
					'id' => 'pictures',
					'type' => 'pictures',
					'caption' => 'Pictures',
					'is_system' => true,
					'value' => '',
				]);

			$listings[($listingInfo['sid'])] = $listing;
		}
		
		return $listings;
	}

	public function deleteListingFromRotation($listingSid)
	{
		return \App()->DB->query("DELETE FROM `classifieds_feature_display_rotator` WHERE `feature_type` = 'Featured' AND `listing_sid` = ?n", $listingSid);
	}
}
