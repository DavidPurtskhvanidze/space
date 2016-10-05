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


namespace modules\listing_comments;

class NotifyListingOwnerAfterAddComment extends \modules\users\apps\FrontEnd\AbstractUserNotification implements IAfterAddListingCommentAction
{
	private $comment;

	public function setComment($comment)
	{
		$this->comment = $comment;
	}

	public function perform()
	{
		$listing = \App()->ListingManager->getObjectBySID($this->comment->getListingSid());
		if ($this->getValue($listing->getUserSid()))
		{
			$listingOwner = \App()->UserManager->getObjectBySid($listing->getUserSid());
			$siteUrl = \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl');

			$this->comment->addProperty(array('type' => 'object', 'id' => 'user', 'value' => \App()->UserManager->getObjectBySID($this->comment->getUserSid())));
			$commentToArrayAdapter = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this->comment);
			$listingToArrayAdapter = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($listing);
			$userToArrayAdapter = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($listingOwner);

			return \App()->EmailService->send($listingOwner->getPropertyValue('email'), 'email_template:new_comment_added', array(
				'user_site_url' => $siteUrl,
				'user' => $userToArrayAdapter,
				'comment' => $commentToArrayAdapter,
				'listing' => $listingToArrayAdapter,
			));
		}
		return false;
	}

	public function getId()
	{
		return 'comment_added';
	}

	public function getCaption()
	{
		return 'Notify on New Comment/Reply Added to your Listing';
	}
}
