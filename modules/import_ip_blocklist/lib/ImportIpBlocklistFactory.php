<?php
/**
 *
 *    Module: import_ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19786, 2016-06-17 13:19:33
 *
 *    This file is part of the 'import_ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_ip_blocklist\lib;

class ImportIpBlocklistFactory
{
	public function createDataTransceiver($config)
	{
		$inputDataSource = $this->getInputDataSource($config);
		$outputDataSource = $this->getOutputDataSource();

		$fieldScheme = $inputDataSource->getFieldsScheme();
		if (!in_array('ip_range', $fieldScheme))
			throw new \lib\DataTransceiver\TransceiveFailedException('FIELD_IP_RANGE_NOT_SET');

		$dataConverter = $this->getDataConverter($inputDataSource->getFieldsScheme());
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

	function getOutputDataSource()
	{
		$datasource = new ImportIpBlocklistOutputDatasource();
		$datasource->setIpRangeManager(\App()->IpRangeManager);
		$datasource->setIpRangeValidator($this->getIpRangeValidator());
		return $datasource;
	}

	function getDataConverter($fieldsScheme)
	{
		$converter = new ImportIpBlocklistDataConverter();
		$converter->setArrayCombiner(\App()->ObjectMother->createArrayCombiner());
		$converter->setFieldsScheme($fieldsScheme);
		$converter->setIpRangeManager(\App()->IpRangeManager);
		$converter->setI18N(\App()->I18N);
		$converter->setIpRangeRequiredFieldsDefiner($this->getIpRangeRequiredFieldsDefiner());
		return $converter;
	}
	
	function getLogger()
	{
		$logger = new \lib\DataTransceiver\Import\ImportLogger();
		return $logger;
	}

	function getIpRangeValidator()
	{
		return new IpRangeValidator();
	}

	function getIpRangeRequiredFieldsDefiner()
	{
		$ipRangeRequiredFieldsDefiner = new IpRangeRequiredFieldsDefiner();
		return $ipRangeRequiredFieldsDefiner;
	}
}
