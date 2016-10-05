<?php
/**
 *
 *    Module: export_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: export_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19779, 2016-06-17 13:19:16
 *
 *    This file is part of the 'export_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\export_listings\lib;

use lib\DataTransceiver\DataTransceiverFactory;
use modules\export_listings\lib\ExportListings;

class ExportListingsFactory
{
	public function createDataTransceiver($requestData, $search)
	{
		$exportListings = new ExportListings();
		$exportListings->setExportFilesDirectory(PATH_TO_ROOT . \App()->SystemSettings['ExportFilesDir']);
		$exportListings->setRequestData($requestData);
		$exportListings->setSearch($search);

		$inputDatasource = $exportListings->getInputDataSource();
		$outputDatasource = $exportListings->getOutputDataSource();
		$dataConverter = $exportListings->getDataConverter();
		$logger = $exportListings->getLogger();
		$validator = $exportListings->getValidator();

		$dataTransceiverFactory = new DataTransceiverFactory();
		$dataTransceiver = $dataTransceiverFactory->createDataTransceiver($inputDatasource, $outputDatasource, $dataConverter, $logger, $validator);
		return $dataTransceiver;
	}
}
