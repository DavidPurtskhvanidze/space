<?php
/**
 *
 *    Module: import_users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_users-7.5.0-1
 *    Tag: tags/7.5.0-1@19788, 2016-06-17 13:19:38
 *
 *    This file is part of the 'import_users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_users\lib;

use lib\DataTransceiver\Import\IImportConfig;

class ImportUsersConfigRequestData implements IImportConfig
{
    private $filePath;
    private $request;
    public function __construct()
    {
        $this->extraData = array
        (
            'csvFileDelimiter' => \App()->Request['csv_delimiter']
        );

        $this->request = \App()->Request;
    }

    public function getExtraDataValue($name)
    {
        return isset($this->extraData[$name]) ? $this->extraData[$name] : null;
    }

    public function getFilePath()
    {
        return $this->filePath;
    }

    public function getImportFormat()
    {
        return $this->request['file_type'];
    }

    public function getActivateUsers()
    {
        return $this->request['activate'];
    }

    public function getUserGroupSid()
    {
        return $this->request['user_group_sid'];
    }

    public function getNotifications()
    {
        return $this->request['notifications'];
    }

    public function getAddListValues()
    {
        return $this->request['non_existed_values'] == 'add';
    }

    public function getLocalFileName()
    {
        return 'importUsersSource';
    }

    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
    }
}
