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

/**
 * After new listing comment added action interface.
 * 
 * Interface designed for performing action after new listing comment is added
 * 
 * @category ExtensionPiont
 */
interface IAfterAddListingCommentAction
{
	/**
	 * Comment object setter
	 * @param \modules\listing_comments\lib\ListingComment
	 */
	public function setComment($comment);
	/**
	 * Action executer
	 */
	public function perform();
}
