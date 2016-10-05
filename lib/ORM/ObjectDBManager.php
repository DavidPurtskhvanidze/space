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

class ObjectDBManager implements \core\IService
{
	const SQL_SELECT_OBJECT_INFO = 'SELECT * FROM `?w` WHERE `sid` = ?n';
	
	public function saveObject($object)
	{
		$db_table_name = $object->getTableName();
		$object_sid = $object->getSID();
		if (is_null($object_sid))
		{
		 	if (!$object_sid = \App()->DB->query("INSERT INTO `" . $db_table_name . "`() VALUES()")) return false;
			else $object->setSID($object_sid);
		}
		else
		{
			\App()->DB->resetCacheForquery("SELECT * FROM `" . $db_table_name . "` WHERE `sid` = ?n", $object_sid);
		}
		$propertiesValues = $this->getObjectPropertiesSqlValues($object);
		$columns = array();
		foreach ($propertiesValues as $id => $value)
		{
			array_push($columns, is_null($value) ? "`$id` = NULL" : "`$id` = $value");
		}
        $columnsList = join(', ', $columns);
		\App()->DB->queryNoReplace("UPDATE `" . $db_table_name . "` SET " . $columnsList . " WHERE `sid` = {$object_sid}");
	}

	public function getObjectPropertiesSqlValues($object)
	{
		$db_table_name = $object->getTableName();
		$object_details = $object->getDetails();
		$object_properties = $object_details->getProperties();
		$propertiesValues = array();
		foreach ($object_properties as $object_property)
		{
			if (!$object_property->saveIntoBD()) continue;
			if ($object_property->getTableName() != $db_table_name) continue;
			$property_id = $object_property->getID();
			// sid is not editable
			if ($property_id == 'sid') continue;

            if ($object_property->hasMultipleColumns())
            {
                $propertiesValues  = array_merge($propertiesValues, $object_property->getSqlValues());
            }
            else
            {
                $property_sql_value = $object_property->getSQLValue();
                $propertiesValues[$property_id] = $property_sql_value;
            }
		}
		return $propertiesValues;
	}

	function getObjectInfo($db_table_name, $object_sid)
	{
		$object_info = \App()->DB->query("SELECT * FROM `" . $db_table_name . "` WHERE `sid` = ?n", $object_sid);
		$object_info = array_pop($object_info);
		return $object_info;
	}
	
	function getObjectInfoCached($table, $listing_field_sid)
	{
		if(is_null($listing_field_sid))
		{
			return null;
		}
		$fields = array();
		if(\App()->MemoryCache->exists('cache for ' . $table))
		{
			$fields = \App()->MemoryCache->get('cache for ' . $table);
		}
		else
		{
			$listing_fields = $this->getObjectsInfoByType($table);
			foreach(array_keys($listing_fields) as $key)
			{
				$listing_field = $listing_fields[$key];
				$fields[$listing_field['sid']] = $listing_field;
			}
			\App()->MemoryCache->set('cache for ' . $table, $fields);
		}
		return isset($fields[$listing_field_sid]) ? $fields[$listing_field_sid] : null;
	}

	function getObjectsInfoByType($db_table_name)
	{
		return \App()->DB->query("SELECT * FROM `" . $db_table_name . "`");
	}
	
	function deleteObjectInfoFromDB($db_table_name, $object_sid)
	{
		return \App()->DB->query("DELETE FROM `" . $db_table_name . "` WHERE `sid` = ?n", $object_sid);
	}

	function deleteObject($db_table_name, $object_sid)
	{
		return $this->deleteObjectInfoFromDB($db_table_name, $object_sid);
	}

	function deleteObjects($db_table_name, $object_sids)
	{
		return \App()->DB->query("DELETE FROM `$db_table_name` WHERE `sid` in (?l)", $object_sids);
	}
	
	function getObjectsInfoBySidCollection($db_table_name, $sid_collection)
	{
        $table = $db_table_name . "_properties";
		if (empty($sid_collection))
		{
			$sid_collection = array('NULL');
		}
		
		$objects_info = array();
		
		if (\App()->DB->table_exists($table))
		{
			$meta_info = \App()->DB->query("SELECT * FROM `" . $table . "` WHERE `object_sid` IN (?l)", $sid_collection);
			
			foreach ($meta_info as $info)
			{
				if (!isset($objects_info[$info['object_sid']]))
					$objects_info[$info['object_sid']] = array();
				$objects_info[$info['object_sid']][$info['id']] = $info['value'];
			}
		}
		
		$system_info = \App()->DB->query("SELECT * FROM `" . $db_table_name . "` WHERE `sid` IN (?l)", $sid_collection);
		
		foreach ($system_info as $info)
		{
			if (!isset($objects_info[$info['sid']]))
			{
				$objects_info[$info['sid']] = array();
			}
			
			foreach ($info as $property => $value)
			{
				$objects_info[$info['sid']][$property] = $value;
			}
		}
		
		return $objects_info;
	}
}
