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

class TreeValuesProcessor
{
	var $addNewValuesToDB;
	var $listingFieldTreeManager;
	var $objectData;
	private $logger;

	function setAddNewValuesToDB($addNewValuesToDB)
	{
		$this->addNewValuesToDB = $addNewValuesToDB;
	}
	function setListingFieldTreeManager($listingFieldTreeManager)
	{
		$this->listingFieldTreeManager = $listingFieldTreeManager;
	}
	function setObjectData($objectData)
	{
		$this->objectData = $objectData;
	}
	
	function processObject($object, $objectData)
	{
		$this->combineTreeFieldValues($objectData);
		$this->setObjectData($objectData);
		$properties = $object->getProperties();
		array_walk($properties, array($this, 'processProperty'));
	}
	function processProperty($property)
	{
		if ($property->getType() != 'tree' || empty($this->objectData[$property->getId()]))
		{
			return;
		}
		$propertyValue = $this->objectData[$property->getId()];
		$treeItemSid = null;
		$parentSid = 0;
		foreach ($propertyValue as $treeItemCaption)
		{
			if (empty($treeItemCaption)) break;
			$treeItemSid = $this->listingFieldTreeManager->getSIDByCaption($property->getSid(), array($parentSid), $treeItemCaption);
			if ($treeItemSid == -1)
			{
				if (!$this->addNewValuesToDB)
				{
					return;
				}
				$treeItemSid = $this->listingFieldTreeManager->addTreeItemToEndByParentSID($property->getSid(), $parentSid, $treeItemCaption);
				$this->logger->logTreeValueAdd();
			}
			$parentSid = $treeItemSid;
		}
		$property->setValue($treeItemSid);
	}
	
	function combineTreeFieldValues(&$data)
	{
		foreach ($data as $key => $value)
		{
			if (preg_match("/([^\[]+)\[(\d+)\]/", $key, $matches))
			{
				$fieldId = $matches[1];
				$level = $matches[2];
				$data[$fieldId][$level] = $value;
				ksort($data[$fieldId]);
				unset($data[$key]);
			}
		}
	}

	public function setLogger($logger)
	{
		$this->logger = $logger;
	}
}
