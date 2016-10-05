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

trait BelongsTo
{
    public function belongsTo($table, $column)
    {
        $columnValues = $this->getColumnValues($column);
        return ['column' => $column, 'values' => \App()->DB->query("SELECT * FROM `{$table}` WHERE `sid` in (?l)", $columnValues)];
    }

    /**
     * @param $column
     * @return array
     */
    private function getColumnValues($column)
    {
        $collection = \App()->MemoryCache->get('Collection_' . get_called_class());
        $values = [];
        foreach($collection as &$info)
                $values[$info[$column]] = $info[$column];
        return $values;
    }
}
