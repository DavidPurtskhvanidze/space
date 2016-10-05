<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\ORM\Rating;

/**
 * Interface for operations after object containing RatingType is rated
 * 
 * Interface designed for providing additional operation after object containing RatingType is rated.
 * 
 * @category ExtensionPiont
 */
interface IAfterObjectRated
{
	/**
	 * Rating manager setter
	 * @param lib\ORM\Rating\RatingManager $ratingManager
	 */
	public function setRatingManager($ratingManager);
	/**
	 * Object sid setter
	 * @param int objectSid
	 */
	public function setObjectSid($objectSid);
	/**
	 * Field sid setter
	 * @param int $fieldSid
	 */
	public function setFieldSid($fieldSid);
	/**
	 * User sid setter
	 * @param int $userSid
	 */
	public function setUserSid($userSid);
	/**
	 * Action executer
	 */
	public function perform();
}
