<?php
/**
 *
 *    Module: I18N v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: I18N-7.5.0-1
 *    Tag: tags/7.5.0-1@19784, 2016-06-17 13:19:28
 *
 *    This file is part of the 'I18N' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\I18N\apps\AdminPanel;

/**
 * Edit phrase validator
 * 
 * Interface designed for validating edit phrase action in AdminPanel. If it returns false, phrase will not be edited.
 * 
 * @category ExtensionPoint
 */
interface IEditPhraseValidator
{
	/**
	 * Setter of phrase data
	 * @param array $phraseData
	 */
	public function setPhraseData($phraseData);
	/**
	 * Action validator
	 * @return boolean
	 */
	public function isValid();
}
