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

class ListingCommentManager extends \lib\ORM\ObjectManager implements \core\IService
{
	private $adminManager;
	
	function init()
	{
		$this->dbManager = new \lib\ORM\ObjectDBManager();
		$this->adminManager = \App()->ObjectMother->createAdmin();
	}
	
	public function saveListingComment($listingComment)
	{
		if (is_null($listingComment->getSID()))
			return $this->addListingComment($listingComment);
		else
			return $this->updateListingComment($listingComment);
	}
	public function addListingComment($listingComment)
	{
		parent::saveObject($listingComment);
		\App()->DB->query("UPDATE `listing_comments` SET `posted` = NOW() WHERE `sid` = ?n", $listingComment->getSID());
	}
	public function updateListingComment($listingComment)
	{
		parent::saveObject($listingComment);
	}
	public function getTree()
	{
		$data = \App()->DB->query("select *, `parent_comment_sid` as parent from `listing_comments`");
		$commentsTree = \App()->ObjectMother->createTreeBuilder();
		$commentsTree->setData($data);
		$commentsTree->buildTree();
		return $commentsTree->getTree();
		
	}
	public function deleteListingComment($listingCommentSid)
	{
		$listingCommentsTree = $this->getTree();
		$idCollector = \App()->ObjectMother->createTreeWalkerHandlerIdCollector();
		$treeWalker = \App()->ObjectMother->createTreeWalker();
		$treeWalker->setHandler($idCollector);
		$treeWalker->walkDown($listingCommentsTree->getItem($listingCommentSid));
		return $this->dbManager->deleteObjects('listing_comments', $idCollector->getIds());
	}
	public function hideListingComment($listingCommentSid)
	{
		return \App()->DB->query("UPDATE `listing_comments` SET `published` = 0 WHERE `sid` = ?n", $listingCommentSid);
	}
	public function publishListingComment($listingCommentSid)
	{
		return \App()->DB->query("UPDATE `listing_comments` SET `published` = 1 WHERE `sid` = ?n", $listingCommentSid);
	}
	public function createListingComment($data = array())
	{
		$instance = new ListingComment();
		$instance->setDetails($this->createListingCommentDetails($data));
		if (!empty($data['sid'])) $instance->setSID($data['sid']);
		return $instance;
	}
	public function createListingCommentDetails($data)
	{
		$instance = new ListingCommentDetails();
		$instance->setOrmObjectFactory(\App()->OrmObjectFactory);
		$instance->buildPropertiesWithData($data);
		return $instance;
	}
	public function getListingCommentBySid($listingCommentSid)
	{
		return $this->createListingComment(parent::getObjectInfoBySID("listing_comments", $listingCommentSid));
	}

	public function getListingCommentInfoBySid($sid)
	{
		return parent::getObjectInfoBySID("listing_comments", $sid);
	}

	public function doesCommentExist($commentSid)
	{
		$count = \App()->DB->getSingleValue("SELECT COUNT(*) FROM listing_comments WHERE sid = ?n", $commentSid);
		return $count == 1;
	}
	public function deleteListingCommentsOfListing($listingSid)
	{
		return \App()->DB->query("DELETE FROM `listing_comments` WHERE `listing_sid` = ?n", $listingSid);
	}
	
	public function getNumberOfRepliesForComment($comment)
	{
		if ($this->adminManager->admin_authed())
		{
			$numberOfReplies = $this->getNumberOfAllRepliesForComment($comment->getSid());
		}
		elseif (!\App()->UserManager->isUserLoggedIn())
		{
			$numberOfReplies = $this->getNumberOfPublishedRepliesForComment($comment->getSid());
		}
		elseif (\App()->UserManager->getCurrentUserSid() == \App()->ListingManager->getUserSIDByListingSID($comment->getListingSid()))
		{
			$numberOfReplies = $this->getNumberOfAllRepliesForComment($comment->getSid());
		}
		else
		{
			$numberOfReplies = \App()->DB->getSingleValue("SELECT COUNT(*) FROM listing_comments WHERE parent_comment_sid = ?n AND (published = 1 OR user_sid = ?n)", $comment->getSid(), \App()->UserManager->getCurrentUserSid());
		}
		return $numberOfReplies;
	}
	private function getNumberOfPublishedRepliesForComment($commentSid)
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM listing_comments WHERE published = 1 AND parent_comment_sid = ?n", $commentSid);
	}
	private function getNumberOfAllRepliesForComment($commentSid)
	{
		return \App()->DB->getSingleValue("SELECT COUNT(*) FROM listing_comments WHERE parent_comment_sid = ?n", $commentSid);
	}
	
	public function getNumberOfCommentsOfListing($listing)
	{
		if ($this->adminManager->admin_authed())
		{
			$numberOfComments = $this->getNumberOfAllCommentsForListing($listing->getSid());
		}
		elseif (!\App()->UserManager->isUserLoggedIn())
		{
			$numberOfComments = $this->getNumberOfPublishedCommentsForListing($listing->getSid());
		}
		elseif (\App()->UserManager->getCurrentUserSid() == $listing->getUserSid())
		{
			$numberOfComments = $this->getNumberOfAllCommentsForListing($listing->getSid());
		}
		else
		{
			$numberOfComments = \App()->DB->getSingleValue("SELECT COUNT(*) FROM listing_comments WHERE listing_sid = ?n AND parent_comment_sid = 0 AND (published = 1 OR user_sid = ?n)", $listing->getSid(), \App()->UserManager->getCurrentUserSid());
		}
		return $numberOfComments;
	}
	private function getNumberOfPublishedCommentsForListing($listingSid)
	{
		$res = \App()->DB->getSingleValue("SELECT COUNT(*) FROM listing_comments WHERE published = 1 AND listing_sid = ?n AND parent_comment_sid = 0", $listingSid);
		return $res;
	}
	private function getNumberOfAllCommentsForListing($listingSid)
	{
		$res = \App()->DB->getSingleValue("SELECT COUNT(*) FROM listing_comments WHERE listing_sid = ?n AND parent_comment_sid = 0", $listingSid);
		return $res;
	}

	public function getUserSidByCommentSid($commentSid)
	{
		return \App()->DB->getSingleValue("SELECT `user_sid` FROM `listing_comments` WHERE `sid` = ?n", $commentSid);
	}
	
	public function fetchEmailAutocompleteData($keyword, $maxRows)
	{
		$keyword = "%{$keyword}%";
		$dataSet = \App()->DB->query('SELECT `title` FROM `listing_comments` WHERE(`title` LIKE ?s) ORDER BY `title` ASC LIMIT ?n', $keyword, $maxRows);
		if (empty($dataSet))
		{
			$dataSet = array();
		}
		
		$result = array();
		foreach($dataSet as $record)
		{
			$result[] = array(
				'value' => $record['title'],
				'label' => $record['title'],
			);
		}
		
		return $result;
	}
}
