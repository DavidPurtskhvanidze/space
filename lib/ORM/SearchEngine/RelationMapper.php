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

trait RelationMapper
{
    private $relationData = [];

    /**
     * @param array $relationData
     */
    public function setRelationData($relationData)
    {
        $this->relationData = $relationData;
    }

    public function mapRelations($data)
    {
        $relationData = $this->relationData;

        foreach($relationData as $relationName => &$relation)
        {
            $data[$relationName] = [];
            if (!empty($relation['values']))
            {
                foreach($relation['values'] as $k => &$v)
                {
                    if ($data[$relation['column']] == $v['sid'])
                    {
                        $data[$relationName] = $v;
                    }
                    elseif ($data['sid'] == $v[$relation['column']])
                    {
                        $data[$relationName][] = $v;
                    }
                }
            }

        }
        return $data;
    }

}
