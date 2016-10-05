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


namespace modules\listing_comments\apps\AdminPanel\scripts;

class DisplayListingCommentsHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $displayName	= 'Display Listing Comment';
	protected $moduleName = 'listing_comments';
	protected $functionName = 'display_listing_comments';

	private $request;
	private $templateProcessor;
	private $defaultRequest = array('parent_comment_sid' => array('equal' => 0));
	
	public function respond()
	{
		$this->getTemplateProcessor()->assign('comments', $this->getComments());
		$this->getTemplateProcessor()->assign('parentCommentSid', $this->getParentCommentSid());
        $this->getTemplateProcessor()->assign("commentsSidToDisplay", $this->getRequest()->get('commentsSidToDisplay'));
        $this->getTemplateProcessor()->assign("oneOfTheParentsIsHidden", $this->getRequest()->get('oneOfTheParentsIsHidden'));
        $this->getTemplateProcessor()->assign("searchId", $this->getRequest()->get('searchId'));
        $this->getTemplateProcessor()->assign("selectedCommets", $this->getRequest()->get('selectedCommets'));
		$template = $this->getRequest()->get('results_template');
		if (is_null($template)) $template = 'display_comments.tpl';
		$this->getTemplateProcessor()->display($template);
	}
	private function getComments()
	{
		$limit = $this->getRequest()->get('limit') > 0 ? $this->getRequest()->get('limit') : null;
		$search = new \lib\ORM\SearchEngine\Search();
		$search->setObjectsPerPage($limit);
		$search->setRequest($this->getSearchRequest());
		$search->setDB(\App()->DB);
        $search->setSortingFields(array('posted'=>'DESC'));
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
		return $this->getRequest()->getHashtable();
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
	private function getParentCommentSid()
	{
		return $this->getRequest()->get("['parent_comment_sid']['equal']");
	}
}
