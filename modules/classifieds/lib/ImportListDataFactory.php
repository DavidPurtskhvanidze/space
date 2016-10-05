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

class ImportListDataFactory
{
	public function createDataTransceiver($config)
	{
		$inputDataSource = $this->getInputDataSource($config);
		$outputDataSource = $this->getOutputDataSource($config);
		$dataConverter = $this->getDataConverter($inputDataSource->getFieldsScheme(), $config);
		$logger = $this->getLogger();

		$dataTransceiverFactory = new \lib\DataTransceiver\DataTransceiverFactory();
		$dataTransceiver = $dataTransceiverFactory->createDataTransceiver($inputDataSource, $outputDataSource, $dataConverter, $logger, null);
		return $dataTransceiver;
	}

	function getInputDataSource($config)
	{
		$importFormat = $config->getImportFormat();
		if (!class_exists($importFormat))
		{
			throw new \lib\DataTransceiver\TransceiveFailedException('UNSUPPORTED_IMPORT_FORMAT');
		}
		$dataSource = new $importFormat;
		$dataSource->setConfig($config);
		$dataSource->init();
		return $dataSource;
	}

	function getOutputDataSource($config)
	{
        $datasource = $config instanceof ImportMultiListDataConfigRequestData
            ? new  ImportMultiListDataOutputDatasource($config)
            : new ImportListDataOutputDatasource();
        $datasource->setListDataManager(new \modules\classifieds\lib\ListingField\ListingFieldListItemManager());
		return $datasource;
	}

	function getDataConverter($fieldsScheme, $config)
	{
		$converter = new ImportListDataConverter();
		$converter->setFieldSid($config->getFieldSid());
		return $converter;
	}
	
	function getLogger()
	{
		$logger = new \lib\DataTransceiver\Import\ImportLogger();
		return $logger;
	}
}
