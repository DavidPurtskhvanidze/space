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


namespace lib\DataTransceiver\Import;

class ListValuesProcessor
{
	var $listItemManager;
	var $addNewValuesToDB;
	private $logger;

	function setAddNewValuesToDB($addNewValuesToDB)
	{
		$this->addNewValuesToDB = $addNewValuesToDB;
	}
	
	function setListItemManager($listItemManager)
	{
		$this->listItemManager = $listItemManager;
	}

	function processObject($object)
	{
		$properties = $object->getProperties();
		array_walk($properties, array($this, 'processListProperty'));
	}
	
	function processListProperty($property)
	{
		if ($property->getType() != 'list') return;

		$propertyValue = $property->getValue();
		if (empty($propertyValue)) return;

		$propertySid = $property->getSid();
		if ($this->listItemManager->doesListItemExistByValue($propertySid, $propertyValue))
		{
			$property->setValue($this->listItemManager->getListItemByValue($propertySid, $propertyValue)->getSID());
		}
		elseif ($this->addNewValuesToDB)
		{
			$this->listItemManager->addListItem($propertySid, $propertyValue);
			$this->logger->logListValueAdd();
			$property->setValue($this->listItemManager->getListItemByValue($propertySid, $propertyValue)->getSID());
		}
		else
		{
			$property->setValue('');
		}
	}

	public function setLogger($logger)
	{
		$this->logger = $logger;
	}
}
