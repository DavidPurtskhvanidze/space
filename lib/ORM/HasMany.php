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

trait HasMany
{
    public function hasMany($table, $column, $order = null)
    {
        $keys = $this->getPrimaryKeys();
        $sql = "SELECT * FROM `{$table}` WHERE `{$column}` in (?l) ";
        if (is_string($order))
        {
            $sql .= 'ORDER BY ' . $order;
        }
        return ['column' => $column, 'values' => \App()->DB->query($sql, $keys)];
    }

    /**
     * @return array
     */
    private function getPrimaryKeys()
    {
        $collection = \App()->MemoryCache->get('Collection_' . get_called_class());
        $values = [];
        foreach($collection as &$info)
                $values[$info['sid']] = $info['sid'];
        return $values;
    }
}
