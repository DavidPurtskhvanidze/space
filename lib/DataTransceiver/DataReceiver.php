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

class DataReceiver
{
	/**
	 * @var IDataConverter
	 */
	private $dataConverter;
	/**
	 * @var IOutputDatasource
	 */
	private $outputDatasource;
	/**
	 * @var IDataTransceiverLogger
	 */
	private $logger;

	public function setLogger($logger)
	{
		$this->logger = $logger;
	}

	public function setOutputDatasource($outputDatasource)
	{
		$this->outputDatasource = $outputDatasource;
	}

	public function setDataConverter($dataConverter)
	{
		$this->dataConverter = $dataConverter;
	}

	public function getLog()
	{
		return $this->logger->getLog();
	}

	public function receive($data)
    {
		$convertedData = $this->dataConverter->getConverted($data);
		if ($this->outputDatasource->canAdd($convertedData))
		{
			$this->outputDatasource->add($convertedData);
			$warningsText = $this->getWarningsText();
			if (!empty($warningsText))
				$this->logger->logWarning($warningsText);
			$this->logger->logSuccess();
		}
		else
		{
			$errorsText = $this->getErrorsText();
			$this->logger->logError($errorsText);
		}
	}

	private function getErrorsText()
	{
		// didn't find better solution
		if (method_exists($this->outputDatasource, 'getErrors'))
		{
			$tp = \App()->getTemplateProcessor();
			$tp->assign('messages', $this->outputDatasource->getErrors());
			return $tp->fetch("miscellaneous^error_messages/wrapper.tpl");

		}
		else
		{
			return \App()->ErrorMessages->fetchMessages();
		}
	}

	private function getWarningsText()
	{
		return \App()->WarningMessages->fetchMessages();
	}

	public function finalize()
	{
		$this->outputDatasource->finalize();
	}
}
