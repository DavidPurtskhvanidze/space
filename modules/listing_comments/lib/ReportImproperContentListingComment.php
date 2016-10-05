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

class ReportImproperContentListingComment implements \modules\miscellaneous\lib\IReportImproperContentObjectType
{
	public function getType()
	{
		return 'comment';
	}

	public function doesObjectExist($objectSid)
	{
		return \App()->ListingCommentManager->doesCommentExist($objectSid);
	}

	public function getMessageTemplateName()
	{
		return 'email_template:admin_report_improper_comment_content';
	}

	public function getMessageParameters($objectSid)
	{
		$comment = \App()->ListingCommentManager->getListingCommentBySid($objectSid);
		$comment->addProperty(array
		(
			'id' => 'listing',
			'type' => 'object',
			'value' => \App()->ListingManager->getObjectBySid($comment->getPropertyValue('listing_sid')),
		));
		return array('comment' => \App()->ObjectToArrayAdapterFactory->getObjectToArrayAdapter($comment));
	}
}
