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


namespace lib\Actions;

class ChoiceAction
{
	var $abilityCriterion;
	var $onSuccess;
	var $onFailure;
	var $status = "SUCCESS";

	function setAbilityCriterion(&$abilityCriterion)
	{
		$this->abilityCriterion = $abilityCriterion;
	}
	function setOnSuccess(&$onSuccess)
	{
		$this->onSuccess = $onSuccess;
	}
	function setOnFailure(&$onFailure)
	{
		$this->onFailure = $onFailure;
	}
	function getStatus()
	{
		return $this->status;
	}
	function perform()
	{
		if ($this->abilityCriterion->isTrue())
		{
			$this->onSuccess->perform();
			$this->status = 'SUCCESS';
		}
		else
		{
			$this->onFailure->perform();
			$this->status = "FAILURE";
		}
	}
}

?>
