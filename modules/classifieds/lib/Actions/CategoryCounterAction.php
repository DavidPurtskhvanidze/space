<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */

namespace modules\classifieds\lib\Actions;


class CategoryCounterAction
{
    private $fieldName = '';
    private $categorySid = 0;

    const TABLE_NAME = 'classifieds_categories';

    /**
     * @param string $fieldName
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @return int
     */
    public function getCategorySid()
    {
        return $this->categorySid;
    }

    /**
     * @param int $categorySid
     */
    public function setCategorySid($categorySid)
    {
        $this->categorySid = $categorySid;
    }

    private function getOldValue()
    {
        return \App()->DB->getSingleValue("SELECT `{$this->fieldName}` FROM `classifieds_categories` WHERE `sid` = ?n", $this->categorySid);
    }

    private function updateValue($v)
    {
        \App()->DB->query("UPDATE `classifieds_categories` SET `{$this->fieldName}` = ?n WHERE `sid` = ?n", $v, $this->categorySid);
    }

    public function increment($i = 1)
    {
        $oldValue = $this->getOldValue();
        $this->updateValue($oldValue + $i);
    }

    public function decrement($i = 1)
    {
        $oldValue = $this->getOldValue();
        $this->updateValue($oldValue - $i);
    }
}
