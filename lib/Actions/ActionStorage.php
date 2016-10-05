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

class ActionStorage
{
	private $storage = array();

	public function init()
	{
		$this->storage = \App()->Session->getValue('ActionStorage_storage');
		if (!is_array($this->storage)) $this->storage = array();
	}

	public function saveAction($action)
	{
		$id = $this->getNextId();
		$this->storage[$id] = serialize($action);
		\App()->Session->setValue('ActionStorage_storage', $this->storage);
		return $id;
	}

	public function deleteAction($actionId)
	{
		if (isset($this->storage[$actionId]))
		{
			unset($this->storage[$actionId]);
		}
		\App()->Session->setValue('ActionStorage_storage', $this->storage);
	}

	public function getAction($actionId)
	{
		if (!isset($this->storage[$actionId]))
		{
			return null;
		}
		return unserialize($this->storage[$actionId]);
	}

	private function getNextId()
	{
		do
		{
			$id = rand(999, 9999);
		} while(array_key_exists($id, $this->storage));
		return $id;
	}
}
