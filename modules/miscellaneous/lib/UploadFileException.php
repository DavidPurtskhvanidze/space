<?php
/**
 *
 *    Module: miscellaneous v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: miscellaneous-7.5.0-1
 *    Tag: tags/7.5.0-1@19800, 2016-06-17 13:20:10
 *
 *    This file is part of the 'miscellaneous' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\miscellaneous\lib;

class UploadFileException extends \Exception
{
	private $fileId;
	private $extraData = array();

	public function getFileId()
	{
		return $this->fileId;
	}

	public function setFileId($fileId)
	{
		$this->fileId = $fileId;
	}

	public function getErrorData()
	{
		return array_merge($this->extraData, array('errorCode' => $this->message, 'fileId' => $this->fileId));
	}

	public function setExtraData($extraData)
	{
		$this->extraData = $extraData;
	}
}
