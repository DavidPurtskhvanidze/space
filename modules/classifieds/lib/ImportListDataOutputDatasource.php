<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib;

class ImportListDataOutputDatasource implements \lib\DataTransceiver\IOutputDatasource
{
	var $listDataManager;
	
	function setListDataManager($listDataValidator)
	{
		$this->listDataManager = $listDataValidator;
	}

	function add($fieldInfo)
	{
		$this->listDataManager->addListItem($fieldInfo['fieldSid'], $fieldInfo['itemValue']);
	}

	function canAdd($fieldInfo)
	{
		if ($this->listDataManager->doesListItemExistByValue($fieldInfo['fieldSid'], $fieldInfo['itemValue']))
		{
			\App()->ErrorMessages->addMessage('DUPLICATED_VALUE');
			return false;
		}
		return true;
	}

	public function finalize()
	{
	}
}
