<?php
/**
 *
 *    Module: poll v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: poll-7.5.0-1
 *    Tag: tags/7.5.0-1@19804, 2016-06-17 13:20:21
 *
 *    This file is part of the 'poll' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\poll\apps\AdminPanel;

/**
 * Delete poll question validator
 * 
 * Interface designed for validating delete poll question action in AdminPanel. If it returns false, poll qeastion will not be deleted.
 * 
 * @category ExtensionPoint
 */
interface IDeletePollQuestionValidator
{
	/**
	 * Setter of question id
	 * @param int $questionId
	 */
	public function setQuestionId($questionId);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
