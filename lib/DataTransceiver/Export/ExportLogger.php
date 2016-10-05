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


namespace lib\DataTransceiver\Export;

class ExportLogger implements \lib\DataTransceiver\IDataTransceiverLogger
{
	public function logSuccess()
	{
	}

	public function logError($errors)
	{
	}

	public function logWarning($warningsText)
	{
	}

	public function getLog()
	{
		return array();
	}
}
