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

class ExportedListingFilesHandler
{
	private $exportFilesDirectory;
	private $directory;
	private $uploadFileManager;
	private $fieldsId;

	public function setFieldsId($fieldsId)
	{
		$this->fieldsId = $fieldsId;
	}
	public function setDirectory($directory)
	{
		$this->directory = $directory;
	}
	public function setExportFilesDirectory($exportFilesDirectory)
	{
		$this->exportFilesDirectory = $exportFilesDirectory;
	}
	public function setUploadFileManager($uploadFileManager)
	{
		$this->uploadFileManager = $uploadFileManager;
	}
	public function handle($exportedListing)
	{
		$data = $exportedListing->getData();
		foreach ($this->fieldsId as $fieldId)
		{
			if (isset($data[$fieldId]) && !empty($data[$fieldId]['file_id']))
			{
				$fileInfo = $this->uploadFileManager->getUploadedFileInfo($data[$fieldId]['file_id']);
				$filePath = "{$this->exportFilesDirectory}{$this->directory}{$fileInfo['saved_file_name']}";
				$this->uploadFileManager->copyFile($data[$fieldId]['file_id'], $filePath);
				$data[$fieldId] = "{$this->directory}{$fileInfo['saved_file_name']}";
			}
		}
		$exportedListing->setData($data);
	}
}
