<?php
/**
 *
 *    Module: export_ip_blocklist v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_ip_blocklist-7.5.0-1
 *    Tag: tags/7.5.0-1@19778, 2016-06-17 13:19:13
 *
 *    This file is part of the 'export_ip_blocklist' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_ip_blocklist\lib;

class ExportIpBlocklistFactory
{
	public function createDataTransceiver($ipRangeSids, $fieldsScheme)
	{
		$exportIpBlocklist = new ExportIpBlocklist();
		$exportIpBlocklist->setIpRangeSids($ipRangeSids);
		$exportIpBlocklist->setFieldsScheme($fieldsScheme);

		$inputDatasource = $exportIpBlocklist->getInputDataSource();
		$outputDatasource = $exportIpBlocklist->getOutputDataSource();
		$dataConverter = $exportIpBlocklist->getDataConverter();
		$logger = $exportIpBlocklist->getLogger();
		$validator = $exportIpBlocklist->getValidator();

		$dataTransceiverFactory = new \lib\DataTransceiver\DataTransceiverFactory();
		$dataTransceiver = $dataTransceiverFactory->createDataTransceiver($inputDatasource, $outputDatasource, $dataConverter, $logger, $validator);
		return $dataTransceiver;
	}
}
