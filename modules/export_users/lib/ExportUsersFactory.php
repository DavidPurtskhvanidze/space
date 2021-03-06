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

class ExportUsersFactory
{
	public function createDataTransceiver($userSids, $fieldsScheme)
	{
		$exportUsers = new ExportUsers();
		$exportUsers->setUserSids($userSids);
		$exportUsers->setFieldsScheme($fieldsScheme);

		$inputDatasource = $exportUsers->getInputDataSource();
		$outputDatasource = $exportUsers->getOutputDataSource();
		$dataConverter = $exportUsers->getDataConverter();
		$logger = $exportUsers->getLogger();
		$validator = $exportUsers->getValidator();

		$dataTransceiverFactory = new \lib\DataTransceiver\DataTransceiverFactory();
		$dataTransceiver = $dataTransceiverFactory->createDataTransceiver($inputDatasource, $outputDatasource, $dataConverter, $logger, $validator);
		return $dataTransceiver;
	}
}
