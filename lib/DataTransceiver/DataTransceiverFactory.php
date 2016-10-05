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


namespace lib\DataTransceiver;

class DataTransceiverFactory
{
	public function createDataTransceiver($inputDatasource, $outputDatasource, $dataConverter, $logger, $validator)
	{
		$dataTransceiver = new DataTransceiver();
		$dataTransmitter = new DataTransmitter();
		$dataReceiver = new DataReceiver();
		
		$dataTransceiver->setDataTransmitter($dataTransmitter);
		$dataTransceiver->setDataReceiver($dataReceiver);
		$dataTransceiver->setValidator($validator);
		
		$dataTransmitter->setInputDatasource($inputDatasource);
		
		$dataReceiver->setDataConverter($dataConverter);
		$dataReceiver->setOutputDatasource($outputDatasource);
		$dataReceiver->setLogger($logger);
		
		return $dataTransceiver;
	}
}
