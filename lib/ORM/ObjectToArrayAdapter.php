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


class ObjectToArrayAdapter implements \ArrayAccess
{
	private $object;
	public function setObject($object){$this->object = $object;}
	public function getObject(){return $this->object;}
	
	private $objectToArrayAdapterUrlSeoDataConverter;
	public function setObjectToArrayAdapterUrlSeoDataConverter($objectToArrayAdapterUrlSeoDataConverter)
	{
		$this->objectToArrayAdapterUrlSeoDataConverter = $objectToArrayAdapterUrlSeoDataConverter;
	}
	
	private $objectToArrayAdapterStringConverter;
	public function setObjectToArrayAdapterStringConverter($objectToArrayAdapterStringConverter)
	{
		$this->objectToArrayAdapterStringConverter = $objectToArrayAdapterStringConverter;
	}
	
	public function getId()
	{
		return $this->object->getId();
	}

	public function offsetGet($index)
	{
//        if ($index == 'picture') {d(get_class($this->object));}
		if ($index == 'urlData') return $this->getObjectUrlSeoData();
		if ($this->object->propertyIsSet($index))
		{
			$propertyDisplay = new ObjectPropertyDisplayValue();
			$propertyDisplay->setObjectProperty($this->object->getProperty($index));
			$propertyDisplay->setNullObjectPropertyDisplayValue(new NullObjectPropertyDisplayValue());
		}
		elseif ($index == 'id' || $index == 'sid')
		{
			return $this->object->getSid(); // Could not find a better solution
		}
		else
		{
			$propertyDisplay = new NullObjectPropertyDisplayValue();
			$propertyDisplay->setObjectPropertyId($index);
		}
		return $propertyDisplay;
	}	
	
	public function offsetExists($index)
	{
		return $this->object->propertyIsSet($index);
	}

	public function offsetSet($offset, $value){throw new \Exception("This is a read-only object");}
	public function offsetUnset($offset){throw new \Exception("This is a read-only object");}
	public function __toString()
	{
        try
        {
            /**
             * @var string $str
             */
            return $this->objectToArrayAdapterStringConverter->getConverted($this);

        } catch (\Exception $e)
        {
            return $e->getMessage();
        }

	}
	public function getObjectUrlSeoData()
	{
		return $this->objectToArrayAdapterUrlSeoDataConverter->getConverted($this);
	}
}
