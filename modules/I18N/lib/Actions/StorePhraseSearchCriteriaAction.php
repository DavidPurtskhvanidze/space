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


namespace modules\I18N\lib\Actions;

class StorePhraseSearchCriteriaAction
{
	var $errors = array();
	var $storage = null;
	var $criteria = null;
	
	function __construct($storage, $criteria)
	{
		$this->storage = $storage;
		$this->criteria = $criteria;
	}
	
	function canPerform()
	{
		return true;
	}
	
	function perform()
	{
		$this->storage->setValue('TRANSLATION_FILTER', $this->criteria);
	}

	function getErrors()
	{
		return $this->errors;
	}
}

?>
