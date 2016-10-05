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


namespace lib\ORM;

class ObjectFilesManager
{
	var $objectManager;
	var $uploadFileManager;
	var $fileTypes = array('video', 'file', 'picture');
	
	function setObjectManager(&$objectManager)
	{
		$this->objectManager = $objectManager;
	}
	function setUploadFileManager(&$uploadFileManager)
	{
		$this->uploadFileManager = $uploadFileManager;
	}
	function deleteFiles($objectSid)
	{
		$object = $this->objectManager->getObjectBySID($objectSid);
		$properties = array_filter($object->getProperties(), array($this, 'isPropertyFileType'));
		array_walk($properties, array($this, 'deleteFileByProperty'));
	}
	function isPropertyFileType(&$property)
	{
		return in_array($property->getType(), $this->fileTypes);
	}
	function deleteFileByProperty($property)
	{
		$this->uploadFileManager->deleteUploadedFileByID($property->value);
	}
}
