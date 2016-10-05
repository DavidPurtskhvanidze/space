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

class SerialActionBatch
{	
	var $actions = array();
	
	function addAction(&$action)
	{
		$this->actions[] = $action;
	}
	
	function canPerform()
	{
		$result = true;
		
		foreach($this->actions as $key => $value)
		{
			$action = $this->actions[$key];
			$result &= $action->canPerform();
		}
		
		return $result;
	}
	
	function perform()
	{
		foreach($this->actions as $key => $value)
		{
			$action = $this->actions[$key];
			$action->perform();
		}
	}

	function getErrors()
	{
		$errors = array();
		
		foreach($this->actions as $key => $value)
		{
			$action = $this->actions[$key];
			$errors = array_merge($action->getErrors(), $errors);
		}
		
		return $errors;
	}
}

?>
