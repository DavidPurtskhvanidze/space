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


namespace lib\ORM\SearchEngine;

class SearchToObjectsCollectionAdapter implements \IteratorAggregate, \ArrayAccess
{
	private $objectToArrayAdapterFactory;
	public function setObjectToArrayAdapterFactory($objectToArrayAdapterFactory)
	{
		$this->objectToArrayAdapterFactory = $objectToArrayAdapterFactory;
	}
	private $search;
	public function setSearch($search){ $this->search = $search; }
	public function getIterator()
	{
		return $this->objectToArrayAdapterFactory->getObjectToArrayWrapperCollectionDecorator($this->search->getFoundObjectCollection());
		
	}
	public function offsetGet($index)
	{
		switch($index)
		{
			case 'count': return $this->search->getNumberOfObjectsFound();
			default: throw new \Exception("Unknown key \"$index\" requested");
		}
	}	
	public function offsetExists($index){return false;}
	public function offsetSet($offset, $value){throw new \Exception("Search is a read-only object");}
	public function offsetUnset($offset){throw new \Exception("Search is a read-only object");}
}
