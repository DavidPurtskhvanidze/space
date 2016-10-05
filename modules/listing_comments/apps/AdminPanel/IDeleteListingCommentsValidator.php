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


namespace modules\listing_comments\apps\AdminPanel;

/**
 * Delete listing comments validator
 * 
 * If returns false, listing comments won't be deleted.
 * 
 * @category ExtensionPoint
 */
interface IDeleteListingCommentsValidator
{
	/**
	 * Setter of listing comment sids
	 * @param array $listingCommentSids
	 */
	public function setListingCommentSids($listingCommentSids);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
