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


namespace modules\listing_comments\apps\FrontEnd\scripts;

class DisplayCommentsHandler extends \apps\FrontEnd\ContentHandlerBase
{
	protected $displayName = 'Display Comments';
	protected $moduleName = 'listing_comments';
	protected $functionName = 'display_comments';
	protected $parameters = array('results_template');

	private $request;
	private $templateProcessor;
	private $defaultRequest = array('parent_comment_sid' => array('equal' => 0));
	
	public function respond()
	{
		$this->extractUriParametersToRequest();
		
		$this->getTemplateProcessor()->assign('comments', $this->getComments());
		$this->getTemplateProcessor()->assign('listingSid', $this->getListingSid());
		$this->getTemplateProcessor()->assign('listingOwnerSid', $this->getListingOwnerSid());
        $this->getTemplateProcessor()->assign('listing', $this->getListingForTemplate());
		$this->getTemplateProcessor()->assign('commentSid', $this->getCommentSid());
        $this->getTemplateProcessor()->assign("messages", $this->getRequest()->get('alertMessage'));
		$template = $this->getRequest()->get('results_template');
		if (is_null($template)) $template = 'display_comments.tpl';
		$this->getTemplateProcessor()->display($template);
	}
	private function extractUriParametersToRequest()
	{
		$parameters_via_url = \App()->UrlParamProvider->getParams();
		if (!empty($parameters_via_url[0]))
		{
			$this->getRequest()->set("listing_sid", array('equal' => $parameters_via_url[0]));
		}
	}
	private function getComments()
	{
		$limit = $this->getRequest()->get('limit') > 0 ? $this->getRequest()->get('limit') : null;
		$search = new \lib\ORM\SearchEngine\Search();
		$search->setObjectsPerPage($limit);
		$search->setRequest($this->getSearchRequest());
		$search->setDB(\App()->DB);
        $search->setSortingFields(array('posted' => 'DESC'));
		$rowMapper = new \modules\listing_comments\lib\ListingCommentRowMapperAdapter(\App()->ListingCommentManager, \App()->UserManager, \App()->ListingManager);
		$rowMapper->setListingFieldManager(\App()->ListingFieldManager);
		$rowMapper->setRatingManager(\App()->ObjectMother->createRatingManager('listing'));
		$search->setRowMapper($rowMapper);
		$listingComment = \App()->ListingCommentManager->createListingComment();
		$listingComment->addProperty(array('id' => 'sid', 'type' => 'integer'));
		$search->setModelObject($listingComment);
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		
				$comments = new \lib\ORM\SearchEngine\SearchToObjectsCollectionAdapter();
		$comments->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);
		$comments->setSearch($search);
		return $comments;
	}
	private function getSearchRequest()
	{
		$publishedCommentsSids = $this->getPublishedCommentsSids();
		$currentUserCommentsSids = $this->getCurrentUserCommentsSids();
		$currentUserListingsCommentsSids = $this->getCurrentUserListingsCommentsSids();
		$commentSids = array_unique(array_merge($currentUserCommentsSids, $currentUserListingsCommentsSids, $publishedCommentsSids));
		if (!empty($commentSids))
			$extraRequest = array('sid' => array('in' => $commentSids));
		else
			$extraRequest = array('published' => array('equal' => 1));
		return array_merge($extraRequest, $this->getRequest()->getHashtable());
	}
	private function getPublishedCommentsSids()
	{
		$search = new \lib\ORM\SearchEngine\Search();
		$search->setDB(\App()->DB);
		$search->setModelObject(\App()->ListingCommentManager->createListingComment());
		$search->setCriterionFactory(\App()->SearchCriterionFactory);
		$search->setObjectsPerPage(null);
		$search->setRequest(array('published' => array('equal' => 1)));
		$publishedCommentsSids = $search->getFoundObjectSidCollection();
		return $publishedCommentsSids;
	}
	private function getCurrentUserCommentsSids()
	{
		$currentUserCommentsSids = array();
		if (\App()->UserManager->isUserLoggedIn())
		{
			$search = new \lib\ORM\SearchEngine\Search();
			$search->setDB(\App()->DB);
			$search->setModelObject(\App()->ListingCommentManager->createListingComment());
			$search->setCriterionFactory(\App()->SearchCriterionFactory);
			$search->setObjectsPerPage(null);
			$search->setRequest(array('user_sid' => array('equal' => \App()->UserManager->getCurrentUserSid())));
			$currentUserCommentsSids = $search->getFoundObjectSidCollection();
		}
		return $currentUserCommentsSids;
	}
	private function getCurrentUserListingsCommentsSids()
	{
		if (!\App()->UserManager->isUserLoggedIn()) return array();
		$currentUserListingsCommentsSids = array();
		$currentUserListingsSid = \App()->ListingManager->getListingsSIDByUserSID(\App()->UserManager->getCurrentUserSid());
		if (!empty($currentUserListingsSid))
		{
			$search = new \lib\ORM\SearchEngine\Search();
			$search->setDB(\App()->DB);
			$search->setModelObject(\App()->ListingCommentManager->createListingComment());
			$search->setCriterionFactory(\App()->SearchCriterionFactory);
			$search->setObjectsPerPage(null);
			$search->setRequest(array('listing_sid' => array('in' => $currentUserListingsSid)));
			$currentUserListingsCommentsSids = $search->getFoundObjectSidCollection();
		}
		return $currentUserListingsCommentsSids;
	}
	private function getRequest()
	{
		if (is_null($this->request))
		{
			$this->request = \App()->ObjectMother->createReflectionFactory()->createHashtableReflector(array_merge($this->defaultRequest, $_REQUEST));
		}
		return $this->request;
	}
	private function getTemplateProcessor()
	{
		if (is_null($this->templateProcessor))
		{
			$this->templateProcessor = \App()->getTemplateProcessor();
		}
		return $this->templateProcessor;
	}
	private function getListingSid()
	{
		return $this->getRequest()->get("['listing_sid']['equal']");
	}
	private function getListingOwnerSid()
	{
		return \App()->ListingManager->getUserSIDByListingSID($this->getListingSid());
	}
	private function getCommentSid()
	{
		return $this->getRequest()->get("['parent_comment_sid']['equal']");
	}
	private function getListingForTemplate()
	{
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter(\App()->ListingManager->getObjectBySid($this->getListingSid()));
	}
}



