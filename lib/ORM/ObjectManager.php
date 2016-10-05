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

class ObjectManager
{
	/**
     *  @var ObjectDBManager
     */
	protected $dbManager;
	
    public function init()
    {
        $this->dbManager = new \lib\ORM\ObjectDBManager();
    }

    function saveObject($object)
	{
		return $this->dbManager->saveObject($object);
	}

	function getObjectInfoBySID($db_table_name, $object_sid)
	{
		if (is_null($object_sid)) return null;
		return $this->dbManager->getObjectInfo($db_table_name, $object_sid);
	}

	/**
	 * @param \lib\ORM\Object $object
	 * @return array
	 */
	function getObjectInfo($object)
	{
		$object_info = array
		(
			'user_defined' => array(),
			'system' => array(),
		);
		$properties = $object->getProperties();
		foreach ($properties as $property)
		{
			$object_info['user_defined'][$property->getID()] = $property->getValue();
			$propertyType = $property->getType();
			if ($propertyType == 'geo' || $propertyType == 'rating') // geodata for google Maps
			{
				$object_info['user_defined'][$property->getID() . '_Data'] = $property->getPropertyVariablesToAssign();
			}
		}

		$object_info['system'] = $this->getSystemObjectInfo($object);
		$object_info['system']['id'] = $object->getID();
		return $object_info;
	}

	function deleteObject($db_table_name, $object_sid)
	{
		return $this->dbManager->deleteObject($db_table_name, $object_sid);
	}

	function getSystemObjectInfo($object)
	{
		$object_system_info = \App()->DB->query("SELECT * FROM `" .$object->getTableName(). "` WHERE `sid` = ?n", $object->getSID());

		if (!empty($object_system_info))
		{
			return array_pop($object_system_info);
		}
		else
		{
	        $system_properties = \App()->DB->query("SHOW COLUMNS FROM `" .$object->getTableName(). "`");

			foreach ($system_properties as $property)
			{
				$object_system_info[$property['Field']] = null;
			}
		}

		return $object_system_info;
	}

	/*** P R I V A T E ***/
	function propertyNameIsAccepted($property_name)
	{
		return ($property_name != 'db_table_name') &&
			   (strpos('details', $property_name) === false);
	}
}
