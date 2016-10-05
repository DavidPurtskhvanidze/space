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

class DataTransceiver
{
	/**
	 * @var DataTransmitter
	 */
	private $dataTransmitter;

	/**
	 * @var DataReceiver
	 */
	private $dataReceiver;

	/**
	 * @var IDataTransceiverValidator
	 */
	private $validator;
	
	public function setValidator($validator)
	{
		$this->validator = $validator;
	}
	
	public function setDataTransmitter($dataTransmitter)
	{
		$this->dataTransmitter = $dataTransmitter;
	}
	
	public function setDataReceiver($dataReceiver)
	{
		$this->dataReceiver = $dataReceiver;
	}
	
	public function getLog()
	{
		return $this->dataReceiver->getLog();
	}
	
	public function getErrors()
	{
		return $this->validator->getErrors();
	}
	
	public function perform()
	{
		while(!$this->dataTransmitter->isEmpty())
		{
			$data = $this->dataTransmitter->transmit();
			if (!empty($data))
			{
				$this->dataReceiver->receive($data);
			}
		}
	}
	
	public function canPerform()
	{
		return $this->validator->isValid();
	}

	public function finalize()
	{
		$this->dataReceiver->finalize();
	}
}
