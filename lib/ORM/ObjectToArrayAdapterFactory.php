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

class ObjectToArrayAdapterFactory implements \core\IService
{
	private $objectToArrayAdapterPrototype;
	public function init()
	{
		$this->objectToArrayAdapterPrototype = new ObjectToArrayAdapter();
		$this->objectToArrayAdapterPrototype->setObjectToArrayAdapterStringConverter($this->createObjectToArrayAdapterStringConverter());
		$this->objectToArrayAdapterPrototype->setObjectToArrayAdapterUrlSeoDataConverter($this->createObjectToArrayAdapterUrlSeoDataConverter());
	}
	public function createObjectToArrayAdapterStringConverter()
	{
		$instance = new ObjectToArrayAdapterStringConverter();
		$instance->setTemplateProcessor(\App()->getTemplateProcessor());
		return $instance;
	}
	public function createObjectToArrayAdapterUrlSeoDataConverter()
	{
		$instance = new ObjectToArrayAdapterUrlSeoDataConverter();
		$instance->setTemplateProcessor(\App()->getTemplateProcessor());
		return $instance;
	}
	public function getObjectToArrayAdapter($object)
	{
		$instance = clone $this->objectToArrayAdapterPrototype;
		$instance->setObject($object);
		return $instance;
	}
	public function getObjectToArrayWrapperCollectionDecorator($collection)
	{
		$instance = new ObjectToArrayWrapperCollectionDecorator();
		$instance->setObjectToArrayAdapterFactory($this);
		$instance->setCollection($collection);
		return $instance;
	}
}
