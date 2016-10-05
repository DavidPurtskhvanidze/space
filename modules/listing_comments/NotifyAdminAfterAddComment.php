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

class NotifyAdminAfterAddComment implements IAfterAddListingCommentAction, \modules\miscellaneous\apps\AdminPanel\IAdminNotification
{
	private $comment;

	public function setComment($comment)
	{
		$this->comment = $comment;
	}

	public function perform()
	{
		if (\App()->SystemSettings['ApplicationID'] != 'AdminPanel' && \App()->SettingsFromDB->getSettingByName($this->getId()))
		{
			$adminSiteUrl = \App()->SystemSettings->getSettingForApp('AdminPanel', 'SiteUrl');

			$this->comment->addProperty(array('type' => 'object', 'id' => 'user', 'value' => \App()->UserManager->getObjectBySID($this->comment->getUserSid())));
			$this->comment->addProperty(array('type' => 'object', 'id' => 'listing', 'value' => \App()->ListingManager->getObjectBySID($this->comment->getListingSid())));
			$commentToArrayAdapter = \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($this->comment);

			$emailTemplate = new \modules\listing_comments\lib\Email\AdminAddCommentEmail();
			return \App()->EmailService->sendToAdmin('email_template:' . $emailTemplate->getId(), array('comment' => $commentToArrayAdapter, 'admin_site_url' => $adminSiteUrl));
		}
		return false;
	}

	public function getId()
	{
		return 'notify_on_comment_added';
	}

	public function getCaption()
	{
		return 'Notify on Comment/Reply Added';
	}
}
