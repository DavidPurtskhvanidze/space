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

class ImportListDataConfigRequestDataFactory
{
    const MAX_MULTULIST_DATA_LENGTH = 64;
    private $fieldInfo;

    public function __construct($fieldInfo)
    {
        $this->fieldInfo = $fieldInfo;
    }

	/**
	 * @throws \lib\DataTransceiver\TransceiveFailedException
	 * @return \lib\DataTransceiver\Import\IImportConfig
	 */
	public function getImportConfig()
    {
        switch ($this->fieldInfo['type'])
        {
            case 'multilist':
                $config = new \modules\classifieds\lib\ImportMultiListDataConfigRequestData();
                $config->setFieldSid($this->fieldInfo['sid']);
                return $config;
                break;
            case 'list':
                $config = new \modules\classifieds\lib\ImportListDataConfigRequestData();
                return $config;
                break;
            default:
                throw new \lib\DataTransceiver\TransceiveFailedException('UNKNOWN_LIST_TYPE');
                break;
        }
    }
}
