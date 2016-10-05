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

class ObjectsInfoToObjectsIterator implements \Iterator
{
	private $rowMapperCallback;
	private $objectsInfo = array();
	private $currentKey;
	private $currentObjectInfo;

	public function current()
	{
		return call_user_func_array($this->rowMapperCallback, array($this->currentObjectInfo));
	}

	public function next()
	{
		list($this->currentKey, $this->currentObjectInfo) = each($this->objectsInfo);
	}

	public function key()
	{
		return $this->currentKey;
	}

	public function valid()
	{
		return !is_null($this->currentObjectInfo);
	}

	public function rewind()
	{
		reset($this->objectsInfo);
		$this->next();
	}

	public function setRowMapperCallback($rowMapperCallback)
	{
		$this->rowMapperCallback = $rowMapperCallback;
	}

	public function setObjectsInfo($objectsInfo)
	{
		$this->objectsInfo = $objectsInfo;
	}
}
