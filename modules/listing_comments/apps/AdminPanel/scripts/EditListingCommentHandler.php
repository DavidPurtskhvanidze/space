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

class EditListingCommentHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $displayName	= 'Edit Listing Comment';
	protected $moduleName = 'listing_comments';
	protected $functionName = 'edit_listing_comment';
	protected $rawOutput = true;

	private $request;
	private $templateProcessor;
	
	public function respond()
	{
		try
		{
			$listingComment = \App()->ListingCommentManager->getListingCommentBySid($this->getRequest()->get('commentSid'));
			$this->getTemplateProcessor()->assign("listingSid", $listingComment->getListingSid());
			$listingComment->deleteProperty('posted');
			$listingComment->incorporateData($this->getRequest()->getHashtable());
			$form = new \lib\Forms\Form($listingComment);
			$form->registerTags($this->getTemplateProcessor());
			if ($this->getRequest()->get('action') == 'save_comment')
			{
				$action = \App()->CommentsActionFactory->createSaveListingCommentAction($listingComment, $form);
				$action->perform();
				$this->getTemplateProcessor()->assign('actionDone', true);
				\App()->SuccessMessages->addMessage('COMMENT_MODIFIED');
			}
			$this->displayForm();
		}
		catch (\modules\listing_comments\lib\NotValidFormDataException $e)
		{
			$this->displayForm();
		}
		catch (\Exception $e)
		{
			$this->getTemplateProcessor()->assign('ERRORS', array($e->getMessage()));
			$this->getTemplateProcessor()->display('errors.tpl');
		}
	}
	private function displayForm()
	{
		$this->getTemplateProcessor()->assign('commentSid', $this->getRequest()->get('commentSid'));
		$this->getTemplateProcessor()->assign('searchId', $this->getRequest()->get('searchId'));
		$this->getTemplateProcessor()->display('edit_comment_form.tpl');
	}
	private function getRequest()
	{
		if (is_null($this->request))
		{
			$this->request = \App()->ObjectMother->createReflectionFactory()->createHashtableReflector($_REQUEST);
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
}
