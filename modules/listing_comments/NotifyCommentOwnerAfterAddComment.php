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

class NotifyCommentOwnerAfterAddComment extends \modules\users\apps\FrontEnd\AbstractUserNotification implements IAfterAddListingCommentAction
{
	private $comment;

	public function setComment($comment)
	{
		$this->comment = $comment;
	}

	public function perform()
	{
		if ($this->comment->getParentCommentSid() == 0) return false;

		$comment = \App()->ListingCommentManager->getListingCommentBySid($this->comment->getParentCommentSid());
		if ($this->getValue($comment->getUserSid()))
		{
			$commentOwner = \App()->UserManager->getObjectBySid($comment->getUserSid());
			$siteUrl = \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl');

			$this->comment->addProperty(array('type' => 'object', 'id' => 'user', 'value' => \App()->UserManager->getObjectBySID($comment->getUserSid())));
			$replyToArrayAdapter = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this->comment);
			$commentToArrayAdapter = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($comment);
			$userToArrayAdapter = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($commentOwner);
			$listingToArrayAdapter = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter(\App()->ListingManager->getObjectBySid($this->comment->getListingSid()));

			return \App()->EmailService->send($commentOwner->getPropertyValue('email'), 'email_template:new_reply_posted', array(
				'user_site_url' => $siteUrl,
				'user' => $userToArrayAdapter,
				'reply' => $replyToArrayAdapter,
				'comment' => $commentToArrayAdapter,
				'listing' => $listingToArrayAdapter,
			));
		}
		return false;
	}

	public function getId()
	{
		return 'reply_posted';
	}

	public function getCaption()
	{
		return 'Notify on New Reply Posted to your Comment';
	}
}
