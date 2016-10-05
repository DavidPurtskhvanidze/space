<?php
/**
 *
 *    Module: export_users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_users-7.5.0-1
 *    Tag: tags/7.5.0-1@19780, 2016-06-17 13:19:18
 *
 *    This file is part of the 'export_users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_users\lib;

class ExportUsers
{
	var $userSids;
	var $fieldsScheme;

	function setUserSids($userSids)
	{
		$this->userSids = $userSids;
	}
	
	function setFieldsScheme($fieldsScheme)
	{
		$this->fieldsScheme = $fieldsScheme;
	}
	
	function getInputDataSource()
	{
		$datasource = new ExportUsersInputDataSource();
		$datasource->setUserSids($this->userSids);
		$datasource->setUserManager(\App()->UserManager);
		return $datasource;
	}
	
	function getOutputDataSource()
	{
		$datasource = new ExportUsersOutputDataSource();
		$datasource->setOutputFileHandler($this->getOutputFileHandler());
		return $datasource;
	}
	
	function getDataConverter()
	{
		$converter = new ExportUsersDataConverter();
		$converter->setFieldsScheme($this->fieldsScheme);
		$converter->setUserManager(\App()->UserManager);
		$converter->setUserGroupManager(\App()->UserGroupManager);
        $converter->setGeoFieldsIds($this->getFieldsIdsByType('geo'));
		return $converter;
	}

    private function getFieldsIdsByType($type)
    {
        $fieldsInfo = \App()->ListingFieldManager->getFieldsInfoByType($type);
        $fieldsIds = array_map(create_function('$fieldInfo', 'return $fieldInfo["id"];'), $fieldsInfo);
        return $fieldsIds;
    }

	function getLogger()
	{
		$logger = new \lib\DataTransceiver\Export\ExportLogger();
		return $logger;
	}
	
	function getValidator()
	{
		$validator = new \lib\DataTransceiver\Export\ExportValidator();
		return $validator;
	}
	
	function getOutputFileHandler()
	{
		$outputFileHandler = new \lib\DataTransceiver\Export\ExportXlsFileHandler();
		$outputFileHandler->setHeadRowData($this->fieldsScheme);
		return $outputFileHandler;
	}
}
