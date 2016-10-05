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

class CommentActionsHandler extends \apps\AdminPanel\ContentHandlerBase
{
	protected $displayName	= 'Listing Comment Actions';
	protected $moduleName = 'listing_comments';
	protected $functionName = 'comment_actions';

	public function respond()
	{
		$this->mapActionToMethod
		(
			array
			(
				'PUBLISH' => array($this, 'publishComment'),
				'HIDE' => array($this, 'hideComment'),
				'DELETE' => array($this, 'deleteComment'),
				'MAKE USER TRUSTED' => array($this, 'makeUserTrusted'),
				'MAKE USER UNTRUSTED' => array($this, 'makeUserUntrusted'),
			)
		);
		throw new \lib\Http\RedirectException(\App()->SystemSettings['SiteUrl'] . $this->getRedirectUri());
	}

	private function getRedirectUri()
	{
		if (!is_null(\App()->Request['returnBackUri']))
		{
			return \App()->Request['returnBackUri'];
		}
		$urlData = array
		(
			'action' => 'restore',
			'searchId' => \App()->Request['searchId'],
			'selectedCommets' => \App()->Request['selectedCommets'],
		);
		$redirectUri = \App()->PageRoute->getSystemPageURI($this->moduleName, 'manage_comments') . '?' . http_build_query($urlData);
		return $redirectUri;
	}

	private function mapActionToMethod($map)
	{
		if (is_null(\App()->Request['action']) || is_null(\App()->Request['selectedCommets']))
		{
			return;
		}
		$action = strtoupper(\App()->Request['action']);
		if (isset($map[$action]))
		{
			call_user_func($map[$action], \App()->Request['selectedCommets']);
		}
	}

	private function publishComment($commentIds)
	{
		$action = \App()->CommentsActionFactory->createPublishListingCommentAction(null);
		foreach($commentIds as $commentId)
		{
			$action->setListingCommentSid($commentId);
			$action->perform();
		}
		\App()->SuccessMessages->addMessage('COMMENTS_PUBLISHED');
	}

	private function hideComment($commentIds)
	{
		$action = \App()->CommentsActionFactory->createHideListingCommentAction(null);
		foreach($commentIds as $commentId)
		{
			$action->setListingCommentSid($commentId);
			$action->perform();
		}
		\App()->SuccessMessages->addMessage('COMMENTS_HIDDEN');
	}

	private function deleteComment($commentIds)
	{
		$canPerform = true;
		$validators = new \core\ExtensionPoint('modules\listing_comments\apps\AdminPanel\IDeleteListingCommentsValidator');
		foreach ($validators as $validator)
		{
			$validator->setListingCommentSids($commentIds);
			$canPerform &= $validator->isValid();
		}
		if (!$canPerform) return;

		$action = \App()->CommentsActionFactory->createDeleteListingCommentAction(null);
		foreach($commentIds as $commentId)
		{
			$action->setListingCommentSid($commentId);
			$action->perform();
		}
		\App()->SuccessMessages->addMessage('COMMENTS_DELETED');
	}

	private function makeUserTrusted($commentIds)
	{
		$parameters = array
		(
			'action' => 'make_user_trusted',
			'returnBackUri' => $this->getRedirectUri(),
			'user_sids' => $this->getUserSidsOfComments($commentIds),
		);
		throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath('users', 'change_user_trusted_status') . '?' . http_build_query($parameters));
	}
	
	private function makeUserUntrusted($commentIds)
	{
		$parameters = array
		(
			'action' => 'make_user_untrusted',
			'returnBackUri' => $this->getRedirectUri(),
			'user_sids' => $this->getUserSidsOfComments($commentIds),
		);
		throw new \lib\Http\RedirectException(\App()->PageRoute->getSystemPagePath('users', 'change_user_trusted_status') . '?' . http_build_query($parameters));
	}

	private function getUserSidsOfComments($commentIds)
	{
		$userSids = array_map(array(\App()->ListingCommentManager, 'getUserSidByCommentSid'), $commentIds);
		$userSids = array_unique($userSids);
		$userSids = array_filter($userSids, function ($userSid)
		{
			return $userSid != 0;
		});
		return $userSids;
	}
}
