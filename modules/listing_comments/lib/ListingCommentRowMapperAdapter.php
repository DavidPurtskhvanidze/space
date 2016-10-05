<?php
/**
 *
 *    Module: listing_comments v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: listing_comments-7.5.0-1
 *    Tag: tags/7.5.0-1@19790, 2016-06-17 13:19:43
 *
 *    This file is part of the 'listing_comments' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\listing_comments\lib;

class ListingCommentRowMapperAdapter
{
	private $factory;
	private $userManager;
	private $listingManager;
	private $listingFieldManager;
	private $ratingManager;

	public function setListingFieldManager($listingFieldManager)
	{
		$this->listingFieldManager = $listingFieldManager;
	}
	public function setRatingManager($ratingManager)
	{
		$this->ratingManager = $ratingManager;
	}
	public function setListingManager($listingManager)
	{
		$this->listingManager = $listingManager;
	}
	
	public function __construct($factory, $userManager, $listingManager)
	{
		$this->factory = $factory;
		$this->userManager = $userManager;
		$this->listingManager = $listingManager;
	}
	public function mapRowToObject($row)
	{
		$comment = $this->factory->createListingComment($row);
		$this->addUserProperty($comment);
		$this->addNumberOfRepliesProperty($comment);

		$listing = $this->listingManager->getObjectBySID($comment->getListingSid());
		$properties = $listing->getDetails()->getProperties();
		$ratingProperties = array();
		foreach ($properties as $property)
		{
			if ($property->getType() == 'rating') 
			{
				$comment->addProperty(
				array
				(
					'id' => $property->getId(),
					'caption' => $property->getCaption(),
					'type' => 'integer',
					'value' => $this->getRatingValue($comment->getListingSid(), $property->getId(), $comment->getUserSid()),
				));
			}
		}
		return $comment;
	}
	private function getRatingValue($listingSid, $propertyId, $userSid)
	{
		$fieldSid = $this->listingFieldManager->getFieldSidById($propertyId);
		return $this->ratingManager->getRatingByUser($listingSid, $fieldSid, $userSid);
		
	}
	private function addUserProperty($comment)
	{
		$comment->addProperty(array('id' => 'user', 'type' => 'object', 'value' => $this->getUser($comment->getUserSid())));
	}
	private function getUser($userSid)
	{
		return $this->userManager->getObjectBySID($userSid);
	}
	private function addNumberOfRepliesProperty($comment)
	{
		$comment->addProperty(array('id' => 'numberOfReplies', 'type' => 'integer', 'value' => $this->factory->getNumberOfRepliesForComment($comment)));
	}
}
