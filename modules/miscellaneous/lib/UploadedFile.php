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

class UploadedFile
{
	private $id;
	private $error;

	public function __construct($id)
	{
		$this->id = $id;
		$this->init();
		if (!empty($this->error))
		{
			$this->throwUploadFileException($this->error);
		}
	}

	public function moveTo($destination)
	{
		/**
		 * If destination file is already exists then it will check if it is writable
		 * else it will check if destination directory is writable
		 */
		if (is_file($destination))
		{
			if (!is_writable($destination))
			{
				$this->throwUploadFileException('MOVE_ERR_DESTINATION_FILE_NOT_WRITABLE', array('file' => $destination));
			}
		}
		elseif (!is_writable(dirname($destination)))
		{
			$this->throwUploadFileException('MOVE_ERR_DESTINATION_DIR_NOT_WRITABLE', array('directory' => dirname($destination)));
		}
		return move_uploaded_file($_FILES[$this->id]['tmp_name'], $destination);
	}

	private function init()
	{
		if (!isset($_FILES[$this->id]))
		{
			$this->error = 'UPLOAD_ERR_NO_FILE';
		}
		elseif ($_FILES[$this->id]['error'] != UPLOAD_ERR_OK)
		{
			switch ($_FILES[$this->id]['error'])
			{
				case UPLOAD_ERR_INI_SIZE:
					$this->error = 'UPLOAD_ERR_INI_SIZE';
					break;
				case UPLOAD_ERR_FORM_SIZE:
					$this->error = 'UPLOAD_ERR_FORM_SIZE';
					break;
				case UPLOAD_ERR_PARTIAL:
					$this->error = 'UPLOAD_ERR_PARTIAL';
					break;
				case UPLOAD_ERR_NO_FILE:
					$this->error = 'UPLOAD_ERR_NO_FILE';
					break;
				case UPLOAD_ERR_NO_TMP_DIR :
					$this->error = 'UPLOAD_ERR_NO_TMP_DIR';
					break;
				case UPLOAD_ERR_CANT_WRITE:
					$this->error = 'UPLOAD_ERR_CANT_WRITE';
					break;
				case UPLOAD_ERR_EXTENSION:
					$this->error = 'UPLOAD_ERR_EXTENSION';
					break;
				default:
					$this->error = 'UPLOAD_ERR_CUSTOM_UNDEFINED';
			}
		}
		elseif (!is_uploaded_file($_FILES[$this->id]['tmp_name']))
		{
			$this->error = 'UPLOAD_CUSTOM_ERR_NOT_UPLOADED_FILE';
		}
	}

	public function getId()
	{
		return $this->id;
	}

	private function throwUploadFileException($error, $extraData = array())
	{
		$uploadFileException = new UploadFileException($error);
		$uploadFileException->setFileId($this->id);
		$uploadFileException->setExtraData($extraData);
		throw $uploadFileException;
	}

	public function getExtension()
	{
		return pathinfo($_FILES[$this->id]['name'], PATHINFO_EXTENSION);
	}

	public function getFileName()
	{
		return $_FILES[$this->id]['name'];
	}
}
