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

class ObjectToArrayWrapperCollectionDecorator implements \Iterator, \Countable
{
	private $objectToArrayAdapterFactory;
	public function setObjectToArrayAdapterFactory($objectToArrayAdapterFactory)
	{
		$this->objectToArrayAdapterFactory = $objectToArrayAdapterFactory;
	}
	private $collection;
	public function setCollection($collection){$this->collection = $collection;}
	
	public function current()
	{
		return $this->objectToArrayAdapterFactory->getObjectToArrayAdapter($this->collection->current());
	}
	
	public function rewind(){ $this->collection->rewind(); }
	public function next(){ $this->collection->next(); }
	public function key(){ return $this->collection->key(); }
	public function valid() { return $this->collection->valid(); }

	public function count()
	{
		return count($this->collection);
	}
}
