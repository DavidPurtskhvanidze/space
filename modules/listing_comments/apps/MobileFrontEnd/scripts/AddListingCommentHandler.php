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


namespace modules\listing_comments\apps\MobileFrontEnd\scripts;

use apps\MobileFrontEnd\ContentHandlerBase;
use core\ExtensionPoint;
use lib\Forms\Form;
use lib\Http\RedirectException;
use modules\listing_comments\lib\Exception;
use modules\listing_comments\lib\NotValidFormDataException;

class AddListingCommentHandler extends ContentHandlerBase
{
	protected $displayName = 'Add a Comment';
	protected $moduleName = 'listing_comments';
	protected $functionName = 'add_listing_comment';

	private $request;
	private $templateProcessor;
	
	public function respond()
	{
		try
		{
			if (!\App()->UserManager->isUserLoggedIn()) throw new UserNotLoggedInException();
			$listingComment = \App()->ListingCommentManager->createListingComment($this->getRequest()->getHashtable());
			$listingComment->setUserSid(\App()->UserManager->getCurrentUserSid());
			$listingComment->setListingSid($this->getListingSid());
			$listingComment->setParentCommentSid($this->getParentCommentSid());
			$form = new Form($listingComment);
			$form->registerTags($this->getTemplateProcessor());
			if ($this->getRequest()->get('action') == 'add_comment')
			{
				$action = \App()->CommentsActionFactory->createSaveListingCommentAction($listingComment, $form);
				$action->perform();

				$afterAddCommentActions = new ExtensionPoint('modules\listing_comments\IAfterAddListingCommentAction');
				foreach ($afterAddCommentActions as $afterAddCommentAction)
				{
					$afterAddCommentAction->setComment($listingComment);
					$afterAddCommentAction->perform();
				}

				if ($listingComment->getPropertyValue('published'))
				{
					\App()->SuccessMessages->addMessage('COMMENT_ADDED_PUBLISHED');
				}
				else
				{
					\App()->SuccessMessages->addMessage('COMMENT_ADDED_WAITING');
				}
				throw new RedirectException(\App()->SystemSettings['SiteUrl'] . \App()->Request['returnBackUri']);
			}
			else
			{
				$this->displayForm();
			}
		}
		catch (UserNotLoggedInException $e)
		{
            $frontEndSiteURL = \App()->SystemSettings->getSettingForApp('FrontEnd', 'SiteUrl');
            $this->getTemplateProcessor()->assign('fe', $frontEndSiteURL);
			$this->getTemplateProcessor()->display('login_required.tpl');
		}
		catch (NotValidFormDataException $e)
		{
			$this->displayForm();
		}
		catch (Exception $e)
		{
			\App()->ErrorMessages->addMessage($e->getMessage());
			$this->getTemplateProcessor()->display('messages.tpl');
		}
	}

	private function displayForm()
	{
		$this->getTemplateProcessor()->assign('listingSid', $this->getListingSid());
		$this->getTemplateProcessor()->assign('listing', $this->getListingForTemplate());
		$this->getTemplateProcessor()->assign('commentSid', $this->getParentCommentSid());
		$this->getTemplateProcessor()->assign('comment', $this->getRepliedComment());
		$this->getTemplateProcessor()->assign('returnBackUri', $this->getRequest()->get('returnBackUri'));
		$this->getTemplateProcessor()->display('add_comment_form.tpl');
	}
	private function getListingSid()
	{
		$listingSid = $this->getRequest()->get('listingSid');
		if (is_null($listingSid))
		{
			throw new Exception('PARAMETERS_MISSED');
		}
		return $listingSid;
	}
	private function getParentCommentSid()
	{
		return !is_null($this->getRequest()->get('commentSid')) ? $this->getRequest()->get('commentSid') : 0;
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
	private function getRepliedComment()
	{
   		$comment = \App()->ListingCommentManager->getListingCommentBySid($this->getParentCommentSid());
   		$commentOwner = \App()->UserManager->getObjectBySid($comment->getUserSid());
   		$comment->addProperty(array('id' => 'user', 'type' => 'object', 'value' => $commentOwner));
   		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($comment);
	}
	private function getListingForTemplate()
	{
		return \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter(\App()->ListingManager->getObjectBySid($this->getListingSid()));
	}
}

class UserNotLoggedInException extends \Exception
{
	public function __construct()
	{
		parent::__construct('User not logged in');
	}
}
