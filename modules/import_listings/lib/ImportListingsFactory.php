<?php
/**
 *
 *    Module: import_listings v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_listings-7.5.0-1
 *    Tag: tags/7.5.0-1@19787, 2016-06-17 13:19:36
 *
 *    This file is part of the 'import_listings' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_listings\lib;

use lib\DataTransceiver\DataTransceiverFactory;
use lib\DataTransceiver\Import\ListValuesProcessor;
use lib\DataTransceiver\Import\TreeValuesProcessor;
use lib\DataTransceiver\TransceiveFailedException;
use modules\classifieds\lib\ListingField\ListingFieldListItemManager;
use modules\import_listings\lib\ImportedListingImageUploader;

class ImportListingsFactory
{
	public function createDataTransceiver($config, $uniqueFieldValues)
	{
		$logger = new ImportLogger();
		$collectionSaver = new ListingCollectionSaver();
		$collectionSaver->setListingsLimit($config->getGroupedQuerySize());

		$inputDataSource = $this->getInputDataSource($config);
		$outputDataSource = $this->getOutputDataSource($config, $logger, $collectionSaver);
		$outputDataSource->setListingsUniqueValues($uniqueFieldValues);

		$dataConverter = $this->getDataConverter($inputDataSource->getFieldsScheme(), $config, $logger, $collectionSaver);

		$dataTransceiverFactory = new DataTransceiverFactory();
		$dataTransceiver = $dataTransceiverFactory->createDataTransceiver($inputDataSource, $outputDataSource, $dataConverter, $logger, null);
		return $dataTransceiver;
	}

	public function getInputDataSource($config)
	{
		$importFormat = $config->getImportFormat();
		if (!class_exists($importFormat))
		{
			throw new TransceiveFailedException('UNSUPPORTED_IMPORT_FORMAT');
		}
		$dataSource = new $importFormat;
		$dataSource->setConfig($config);
		$dataSource->init();
		return $dataSource;
	}

	private function getOutputDataSource($config, $logger, $collectionSaver)
	{
		$outputDataSourceClassName = $config->getOutputDataSourceClassName();
		$dataSource = new $outputDataSourceClassName();
		$uniqueFieldSid = $config->getUniqueFieldSid();
		$uniqueFieldId = !is_null($uniqueFieldSid) ? \App()->ListingFieldManager->getListingFieldIDBySID($uniqueFieldSid) : null;
		$dataSource->setUniqueFieldId($uniqueFieldId);
		$dataSource->setUpdateOnMatch($config->getUpdateOnMatch());
		$dataSource->setDeleteOnMiss($config->getDeleteOnMiss());
		$dataSource->setListValuesProcessor($this->getListValuesProcessor($config, $logger));
		$dataSource->setTreeValuesProcessor($this->getTreeValuesProcessor($config, $logger));
		$dataSource->setListingManager(\App()->ObjectMother->createListingManager());
		$dataSource->setImportedListingImageUploader($this->getImportedListingImageUploader($config, $logger, $collectionSaver));
		$dataSource->setLogger($logger);
		$dataSource->setCollectionSaver($collectionSaver);
		return $dataSource;
	}

	private function getListValuesProcessor($config, $logger)
	{
		$listValuesProcessor = new ListValuesProcessor();
		$listValuesProcessor->setLogger($logger);
		$listValuesProcessor->setAddNewValuesToDB($config->getAddListValues());
		$listValuesProcessor->setListItemManager(new ListingFieldListItemManager());
		return $listValuesProcessor;
	}

	private function getTreeValuesProcessor($config, $logger)
	{
		$treeValuesProcessor = new TreeValuesProcessor();
		$treeValuesProcessor->setLogger($logger);
		$treeValuesProcessor->setAddNewValuesToDB($config->getAddTreeValues());
		$treeValuesProcessor->setListingFieldTreeManager(\App()->ListingFieldTreeManager);
		return $treeValuesProcessor;
	}

	/**
	 * @param IImportListingsConfig $config
	 * @param $logger
	 * @param $collectionSaver
	 * @return ImportedListingImageUploader
	 */
	private function getImportedListingImageUploader($config, $logger, $collectionSaver)
	{
		$listingGallery = \App()->ListingGalleryManager->createListingGallery();
		$listingPictureStorageMethod = $config->getListingPictureStorageMethod();
		if (!empty($listingPictureStorageMethod))
		{
			$listingGallery->setListingPictureStorageMethod($listingPictureStorageMethod);
		}

		$instance = new ImportedListingImageUploader();
		$instance->setLogger($logger);
		$instance->setCollectionSaver($collectionSaver);
		$instance->setImportFilesDir(PATH_TO_ROOT . \App()->SystemSettings['ImportFilesDir']);
		$instance->setListingGallery($listingGallery);
		return $instance;
	}

	private function getDataConverter($fieldsScheme, $config, $logger, $collectionSaver)
	{
		$dataConverterClassName = $config->getDataConverterClassName();
		$converter = new $dataConverterClassName();
		$converter->setDefaultUserSid($config->getDefaultUserSid());
		$converter->setArrayCombiner(\App()->ObjectMother->createArrayCombiner());
		$converter->setFieldsScheme($fieldsScheme);
		$converter->setCategorySid($config->getDefaultCategorySid());
		$converter->setImportedListingCreator($this->getImportedListingCreator());
		$converter->setUserManager(\App()->UserManager);
		$packageManager = \App()->ObjectMother->createPackageManager();
		$converter->setPackageInfo($packageManager->getPackageInfoBySID($config->getListingPackageSid()));
		$converter->setActivateListing($config->getActivateListing());
		$converter->setActivationDate($config->getActivationDate());
		$converter->setI18N(\App()->I18N);
		$converter->setCategoryManager(\App()->CategoryManager);
		$converter->setVideoFieldsIds($this->getVideoFieldsIds());
		$converter->setFileFieldsIds($this->getFileFieldsIds());
		$converter->setConfig($config);
		$converter->setLogger($logger);
		$converter->setCollectionSaver($collectionSaver);
		$converter->init();
		return $converter;
	}

	private function getVideoFieldsIds()
	{
		$listingFieldManager = \App()->ObjectMother->createListingFieldManager();
		$fieldsInfo = $listingFieldManager->getFieldsInfoByType('video');
		return array_map(create_function('$fieldInfo', 'return $fieldInfo["id"];'), $fieldsInfo);
	}
	private function getFileFieldsIds()
	{
		$listingFieldManager = \App()->ObjectMother->createListingFieldManager();
		$fieldsInfo = $listingFieldManager->getFieldsInfoByType('file');
		return array_map(create_function('$fieldInfo', 'return $fieldInfo["id"];'), $fieldsInfo);
	}
	private function getImportedListingCreator()
	{
		$instance = new \modules\import_listings\lib\ImportedListingCreator();
		$instance->setListingCreator(\App()->ObjectMother);
		return $instance;
	}
}
