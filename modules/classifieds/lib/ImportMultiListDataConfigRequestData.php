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

namespace modules\classifieds\lib;

class ImportMultiListDataConfigRequestData extends ImportListDataConfigRequestData
{
    private $fieldSID;
    const MAX_MULTULIST_DATA_LENGTH = 64;

    public function setFieldSid($fieldSid)
    {
        $this->fieldSID = $fieldSid;
    }

    public  function getFieldSid()
    {
        return $this->fieldSID;
    }

    public function getMaxItems()
    {
        return self::MAX_MULTULIST_DATA_LENGTH;
    }
}
