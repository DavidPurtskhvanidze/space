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


namespace modules\listing_comments\lib\Actions;

class SaveListingCommentAction
{
	private $listingCommentManager;
	private $listingComment;
	private $userManager;
	private $adminManager;
	private $form;

	public function setForm($form)
	{
		$this->form = $form;
	}
	public function setAdminManager($adminManager)
	{
		$this->adminManager = $adminManager;
	}
	public function setUserManager($userManager)
	{
		$this->userManager = $userManager;
	}
	public function setListingComment($listingComment)
	{
		$this->listingComment = $listingComment;
	}
	public function setListingCommentManager($listingCommentManager)
	{
		$this->listingCommentManager = $listingCommentManager;
	}
	
	public function perform()
	{
		if (!$this->form->isDataValid())
		{
			throw new \modules\listing_comments\lib\NotValidFormDataException();
		}
		if (!$this->adminManager->admin_authed() && !$this->userManager->isUserLoggedIn())
		{
			throw new \Exception('NOT_LOGGED_IN');
		}

		if (is_null($this->listingComment->getSID()))
		{
			$public = false;
			if ($this->adminManager->admin_authed()
				|| $this->userManager->isUserTrusted($this->userManager->getCurrentUserSID()))
			{
				$public = true;
			}
			$this->listingComment->setPublic($public);
		}

        if (!$this->adminManager->admin_authed() && $this->userManager->isUserLoggedIn())
        {
            $this->listingComment->setLastUserIp(\modules\ip_blocklist\lib\IpProcessor::getClientIpAsString());
        }
		$this->listingCommentManager->saveListingComment($this->listingComment);
	}
}
