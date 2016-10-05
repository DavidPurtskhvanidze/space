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


class ObjectDetails
{
    protected $OrmObjectFactory;

    public function setOrmObjectFactory($f)
    {
        $this->OrmObjectFactory = $f;
    }

    /**
     * @var ObjectProperty[]
     */
    protected $properties;
    protected $object_sid;
    protected $object;
    protected $tableName;
    protected $tableAlias;
    protected $objectType;
    protected $detailsInfo;

    public $data = [];

    public function setDetailsInfo($detailsInfo)
    {
        $this->detailsInfo = $detailsInfo;
    }

    public function getDetailsInfo()
    {
        return $this->detailsInfo;
    }

    function buildProperties()
    {
        $details_info = $this->getDetailsInfo();
        foreach ($details_info as $detail_info) {
            if (!isset($detail_info['value'])) $detail_info['value'] = null;
            $detail_info['table_alias'] = isset($detail_info['table_alias']) ? $detail_info['table_alias'] : (isset($detail_info['table_name']) ? $detail_info['table_name'] : $this->getTableAlias()); //!!
            $detail_info['table_name'] = isset($detail_info['table_name']) ? $detail_info['table_name'] : $this->tableName;
            $detail_info['object_type'] = $this->objectType;
            $this->properties[$detail_info['id']] = $this->OrmObjectFactory->createObjectProperty($detail_info);
        }
    }

    public function incorporateData($data)
    {
        $this->data = $data;
        $propertyKeys = array_keys($this->properties);
        foreach ($propertyKeys as $id) if (isset($data[$id])) $this->properties[$id]->setValue($data[$id]);
    }

    public function __get($key)
    {
        return $this->data[$key];
    }

    public function buildPropertiesWithData($data)
    {
        $this->buildProperties();
        $this->incorporateData($data);
    }

    function getSavablePropertyValues()
    {
        foreach ($this->properties as $key => $property) {
            $savable_property_values[$key] = $this->properties[$key]->getSavableValue();
        }

        return $savable_property_values;
    }

    function &getProperties()
    {
        return $this->properties;
    }

    function setObjectSID($sid)
    {
        foreach ($this->properties as $key => $property) {
            $this->properties[$key]->setObjectSID($sid);
        }

        $this->object_sid = $sid;
    }

    function setObject($object)
    {
        foreach ($this->properties as & $property) {
            $property->setObject($object);
        }

        $this->object = $object;
    }

    function addProperty($property_info)
    {
        $property_info['caption'] = isset($property_info['caption']) ? $property_info['caption'] : '';
        $property_info['length'] = isset($property_info['length']) ? $property_info['length'] : '20';
        $property_info['is_required'] = isset($property_info['is_required']) ? $property_info['is_required'] : false;
        $property_info['is_system'] = isset($property_info['is_system']) ? $property_info['is_system'] : false; //!!
        $property_info['table_alias'] = isset($property_info['table_alias']) ? $property_info['table_alias'] : (isset($property_info['table_name']) ? $property_info['table_name'] : $this->getTableAlias()); //!!
        $property_info['table_name'] = isset($property_info['table_name']) ? $property_info['table_name'] : $this->getTableName(); //!!
        $property_info['column_name'] = isset($property_info['column_name']) ? $property_info['column_name'] : $property_info['id']; //!!
        $this->properties[$property_info['id']] = $this->OrmObjectFactory->createObjectProperty($property_info);
        $this->properties[$property_info['id']]->setObjectSID($this->object_sid);
        $this->properties[$property_info['id']]->setObject($this->object);
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    public function getTableAlias()
    {
        return isset($this->tableAlias) ? $this->tableAlias : $this->tableName;
    }

    /**
     * @param string $property_id
     * @return ObjectProperty|null
     */
    function getProperty($property_id)
    {

        if ($this->propertyIsSet($property_id)) {

            $res = isset($this->properties[$property_id]) ? $this->properties[$property_id] : null;

            if (is_null($res) && method_exists($this, $this->getLazyLoadPropertyMethodName($property_id))) {
                $this->{$this->getLazyLoadPropertyMethodName($property_id)}();
                $res = isset($this->properties[$property_id]) ? $this->properties[$property_id] : null;
            }
        } else {
            $res = null;
        }
        return $res;
    }

    function deleteProperty($property_id)
    {
        unset($this->properties[$property_id]);
    }

    function makePropertyRequired($property_id)
    {
        $this->properties[$property_id]->makeRequired();
    }

    function makePropertyNotRequired($property_id)
    {
        $this->properties[$property_id]->makeNotRequired();
    }

    function dontSaveProperty($property_id)
    {
        $this->properties[$property_id]->setDontSaveFlag();
    }

    function propertyIsSet($property_id)
    {
        return isset($this->properties[$property_id]) ? true : method_exists($this, $this->getLazyLoadPropertyMethodName($property_id));
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getLazyLoadPropertyMethodName($property_name)
    {
        return 'lazyLoad' . str_replace(' ', '', ucwords(str_replace('_', ' ', $property_name))) . 'Property';
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
