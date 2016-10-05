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

class CommentsActionFactory implements \core\IService
{
	private $listingCommentManager;
	private $adminManager;
	private $userManager;
	
	public function init()
	{
		$this->listingCommentManager = \App()->ListingCommentManager;
		$this->adminManager = \App()->ObjectMother->createAdmin();
		$this->userManager = \App()->UserManager;
	}
	
	public function setListingCommentManager($listingCommentManager)
	{
		$this->listingCommentManager = $listingCommentManager;
	}
	public function setAdminManager($adminManager)
	{
		$this->adminManager = $adminManager;
	}
	public function setUserManager($userManager)
	{
		$this->userManager = $userManager;
	}
	
	public function createSaveListingCommentAction($listingComment, $form)
	{
		$instance = new \modules\listing_comments\lib\Actions\SaveListingCommentAction();
		$instance->setListingCommentManager($this->listingCommentManager);
		$instance->setAdminManager($this->adminManager);
		$instance->setUserManager($this->userManager);
		$instance->setForm($form);
		$instance->setListingComment($listingComment);
		return $instance;
	}
	public function createDeleteListingCommentAction($listingCommentSid)
	{
		$instance = new \modules\listing_comments\lib\Actions\DeleteListingCommentAction();
		$instance->setListingCommentManager($this->listingCommentManager);
		$instance->setListingCommentSid($listingCommentSid);
		return $instance;
	}
	public function createHideListingCommentAction($listingCommentSid)
	{
		$instance = new \modules\listing_comments\lib\Actions\HideListingCommentAction();
		$instance->setListingCommentManager($this->listingCommentManager);
		$instance->setListingCommentSid($listingCommentSid);
		return $instance;
	}
	public function createPublishListingCommentAction($listingCommentSid)
	{
		$instance = new \modules\listing_comments\lib\Actions\PublishListingCommentAction();
		$instance->setListingCommentManager($this->listingCommentManager);
		$instance->setListingCommentSid($listingCommentSid);
		return $instance;
	}
}
