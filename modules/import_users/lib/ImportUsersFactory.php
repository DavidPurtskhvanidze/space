<?php
/**
 *
 *    Module: import_users v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: import_users-7.5.0-1
 *    Tag: tags/7.5.0-1@19788, 2016-06-17 13:19:38
 *
 *    This file is part of the 'import_users' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\import_users\lib;

use lib\DataTransceiver\DataTransceiverFactory;
use lib\DataTransceiver\Import\ImportLogger;
use lib\DataTransceiver\Import\ListValuesProcessor;
use lib\DataTransceiver\TransceiveFailedException;
use modules\users\lib\UserProfileField\UserProfileFieldListItemManager;

class ImportUsersFactory
{
	public function createDataTransceiver($config)
	{
		$logger = $this->getLogger();
		$inputDataSource = $this->getInputDataSource($config);
		$outputDataSource = $this->getOutputDataSource($config, $logger);

		$fieldScheme = $inputDataSource->getFieldsScheme();
		if (!in_array('username', $fieldScheme))
			throw new TransceiveFailedException('FIELD_USERNAME_NOT_SET');

		$dataConverter = $this->getDataConverter($fieldScheme, $config);

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

    public function getOutputDataSource($config, $logger)
	{
		$datasource = new ImportUsersOutputDatasource();

        return $datasource
            ->setListValuesProcessor($this->getListValuesProcessor($config, $logger))
            ->setUserManager(\App()->UserManager)
		    ->setActivateUser($config->getActivateUsers())
		    ->setUserValidator($this->getUserValidator())
            ->setAcivateNotifications($config->getNotifications());
	}

    public function getDataConverter($fieldsScheme, $config)
	{
		$converter = new ImportUsersDataConverter();
		$converter->setArrayCombiner(\App()->ObjectMother->createArrayCombiner());
		$converter->setFieldsScheme($fieldsScheme);
		$converter->setUserGroupManager(\App()->UserGroupManager);
		$converter->setUserCreator(\App()->ObjectMother);
		$converter->setDefaultUserGroupSid($config->getUserGroupSid());
		$converter->setUserRequiredFieldsDefiner($this->getUserRequiredFieldsDefiner());
		return $converter;
	}

    public function getLogger()
	{
		$logger = new ImportLogger();
		return $logger;
	}

    public function getUserValidator()
	{
		return new UserValidator();
	}

    public function getListValuesProcessor($config, $logger)
	{
		$listValuesProcessor = new ListValuesProcessor();
		$listValuesProcessor->setAddNewValuesToDB($config->getAddListValues());
		$listValuesProcessor->setListItemManager($this->getListItemManager());
		$listValuesProcessor->setLogger($logger);
		return $listValuesProcessor;
	}

    public function getListItemManager()
	{
		return new UserProfileFieldListItemManager();
	}

    public function getUserRequiredFieldsDefiner()
	{
		return new UserRequiredFieldsDefiner();
	}
}
