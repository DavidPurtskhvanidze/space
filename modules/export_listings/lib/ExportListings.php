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

class ExportListings
{
	var $requestData;
	var $search;
	var $exportFilesDirectory;
	var $env;

	public function setExportFilesDirectory($exportFilesDirectory)
	{
		$this->exportFilesDirectory = $exportFilesDirectory;
	}

	public function setSearch($search)
	{
		$this->search = $search;
	}

	public function setRequestData($requestData)
	{
		$this->requestData = $requestData;
	}

	function getInputDataSource()
	{
		$datasource = new ExportListingsInputDataSource();
		$datasource->setCollection($this->getListingsCollection());
		return $datasource;
	}
	
	function getOutputDataSource()
	{
		$datasource = new ExportListingsOutputDataSource();
		$datasource->setExportedListingPicturesHandler($this->getExportedListingPicturesHandler());
		$datasource->setExportedListingVideosHandler($this->getExportedListingVideosHandler());
		$datasource->setExportedListingFilesHandler($this->getExportedListingFilesHandler());
		$datasource->setExportedListingTreeFieldsHandler($this->getExportedListingTreeFieldsHandler());
		$datasource->setExportXlsFileHandler($this->getExportXlsFileHandler());
		$datasource->setArchiver($this->getArchiver());
		$datasource->setExportListingsEnvironment($this->getExportListingsEnvironment());
		return $datasource;
	}
	function getDataConverter()
	{
		$converter = new ExportListingsDataConverter();
		$converter->setFieldsScheme($this->requestData->get('export_properties'));
		$converter->setUserManager(\App()->UserManager);
		$converter->setCategoryManager(\App()->CategoryManager);
		$converter->setGeoFieldsIds($this->getFieldsIdsByType('geo'));
		return $converter;
	}
	private function getFieldsIdsByType($type)
	{
		$fieldsInfo = \App()->ListingFieldManager->getFieldsInfoByType('geo');
		$fieldsIds = array_map(create_function('$fieldInfo', 'return $fieldInfo["id"];'), $fieldsInfo);
		return $fieldsIds;
	}
	
	function getLogger()
	{
		$logger = new \lib\DataTransceiver\Export\ExportLogger();
		return $logger;
	}
	
	function getValidator()
	{
		$validator = new ExportListingsValidator();
		$validator->setRequestData($this->requestData);
		$validator->setSearch($this->search);
		return $validator;
	}
	
	function getExportXlsFileHandler()
	{
		$outputFileHandler = new \lib\DataTransceiver\Export\ExportXlsFileHandler();
		$outputFileHandler->setHeadRowData($this->getXlsFileHeadRowData());
		$outputFileHandler->setFilename($this->exportFilesDirectory . "export_data.xls");
		return $outputFileHandler;
	}
	private function getXlsFileHeadRowData()
	{
		$fieldScheme = $this->requestData->get('export_properties');
		if (empty($fieldScheme)) return $fieldScheme;
		$fieldsInfo = \App()->ListingFieldManager->getFieldsInfoByType('tree');
		foreach ($fieldsInfo as $fieldInfo)
		{
			if (in_array($fieldInfo['id'], $fieldScheme))
			{
				$treeDepth = \App()->ListingFieldTreeManager->getTreeDepthBySID($fieldInfo['sid']);
				$treeFieldIds = array();
				for ($i = 1; $i <= $treeDepth; $i++)
				{
					$treeFieldIds[] = $fieldInfo['id'] . "[$i]";
				}
				$pos = array_search($fieldInfo['id'], $fieldScheme);
				array_splice($fieldScheme, $pos, 1, $treeFieldIds);
			}
		}
		return $fieldScheme;
	}
	
	public function getListingsCollection()
	{
		$collection = $this->search->getFoundObjectCollection();
		$collection->rewind();
		return $collection;
	}
	
	private function getArchiver()
	{
		$archiver = new ExportArchiver();
		$archiver->setName($this->exportFilesDirectory . 'export.tar.gz');
		$archiver->setRemoveDir($this->exportFilesDirectory);
		$archiver->setFilesList(array($this->exportFilesDirectory . "pictures/", $this->exportFilesDirectory . "videos/", $this->exportFilesDirectory . "files/", $this->exportFilesDirectory . "export_data.xls"));
		return $archiver;
	}
	
	private function getExportedListingPicturesHandler()
	{
		$handler = new ExportedListingPicturesHandler();
		$handler->setExportFilesDirectory($this->exportFilesDirectory);
		$handler->setPicturesDirectory('pictures/');
		$handler->setListingGallery(\App()->ListingGalleryManager->createListingGallery());
		return $handler;
	}
	
	private function getExportedListingVideosHandler()
	{
		$fieldsInfo = \App()->ListingFieldManager->getFieldsInfoByType('video');
		$fieldsIds = array_map(create_function('$fieldInfo', 'return $fieldInfo["id"];'), $fieldsInfo);
		
		$handler = new ExportedListingFilesHandler();
		$handler->setExportFilesDirectory($this->exportFilesDirectory);
		$handler->setDirectory("videos/");
		$handler->setUploadFileManager(\App()->UploadFileManager);
		$handler->setFieldsId($fieldsIds);
		return $handler;
	}
	private function getExportedListingFilesHandler()
	{
		$fieldsInfo = \App()->ListingFieldManager->getFieldsInfoByType('file');
		$fieldsIds = array_map(create_function('$fieldInfo', 'return $fieldInfo["id"];'), $fieldsInfo);
		
		$handler = new ExportedListingFilesHandler();
		$handler->setExportFilesDirectory($this->exportFilesDirectory);
		$handler->setDirectory("files/");
		$handler->setUploadFileManager(\App()->UploadFileManager);
		$handler->setFieldsId($fieldsIds);
		return $handler;
	}
	private function getExportListingsEnvironment()
	{
		if (is_null($this->env))
		{
			$this->env = new ExportListingsEnvironment();
			$this->env->setBasedir($this->exportFilesDirectory);
			$this->env->setFileSystem(\App()->FileSystem);
			$this->env->prepare();
		}
		return $this->env;
	}
	
	private function getExportedListingTreeFieldsHandler()
	{
		$fieldsInfo = \App()->ListingFieldManager->getFieldsInfoByType('tree');
		$fieldsIds = array_map(create_function('$fieldInfo', 'return $fieldInfo["id"];'), $fieldsInfo);
		
		$handler = new ExportedListingTreeFieldsHandler();
		$handler->setFieldsId($fieldsIds);
		return $handler;
	}
}
