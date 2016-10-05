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

use lib\DataTransceiver\IDataConverter;
use modules\classifieds\lib\ListingField\ListingFieldMultiListItemManager;

class ImportListingsDataConverter implements IDataConverter
{
	var $arrayCombiner;
	var $fieldsScheme;
	var $categorySid;
	/**
	 * @var ImportedListingCreator
	 */
	var $importedListingCreator;
	var $userManager;
	var $i18n;
	var $packageInfo;
	var $activateListing;
	var $activationDate;
	var $categoryManager;
	protected $videoFieldsIds;
	protected $fileFieldsIds;
	private $mapper;
	private $defaultUserSid;
	private $feedSid;
	private $listingFieldMultiListItemManager;
	private $fieldSidsById = array();

	protected $config;

	public function __construct()
	{
		$this->listingFieldMultiListItemManager = new ListingFieldMultiListItemManager();
	}

	public function setFileFieldsIds($fileFieldsIds)
	{
		$this->fileFieldsIds = $fileFieldsIds;
	}
	public function setVideoFieldsIds($videoFieldsIds)
	{
		$this->videoFieldsIds = $videoFieldsIds;
	}
	function setCategoryManager($categoryManager)
	{
		$this->categoryManager = $categoryManager;
	}
	function setImportedListingCreator($importedListingCreator)
	{
		$this->importedListingCreator = $importedListingCreator;
	}
	function setFieldsScheme($fieldsScheme)
	{
		$this->fieldsScheme = $fieldsScheme;
	}
	function setArrayCombiner($arrayCombiner)
	{
		$this->arrayCombiner = $arrayCombiner;
	}
	function setCategorySid($categorySid)
	{
		$this->categorySid = $categorySid;
	}
	function setUserManager($userManager)
	{
		$this->userManager = $userManager;
	}
	function setI18N($i18n)
	{
		$this->i18n = $i18n;
	}
	function setPackageInfo($packageInfo)
	{
		$this->packageInfo = $packageInfo;
	}
	function setActivateListing($activateListing)
	{
		$this->activateListing = $activateListing;
	}
	function setActivationDate($activationDate)
	{
		$this->activationDate = $activationDate;
	}
	function defineListingOwner($listing, $listingData)
	{
		if (!is_null($this->defaultUserSid))
		{
			$listing->setUserSid($this->defaultUserSid);
		}
		elseif (!empty($listingData['username']))
		{
			$userSid = $this->userManager->getUserSIDbyUsername($listingData['username']);
			$listing->setUserSid($userSid);
		}
	}
	function defineListingPackageInfo($listing)
	{
		$listing->setListingPackageInfo($this->packageInfo);
	}
	function defineListingActiveStatus($listing)
	{
		$listing->setActive($this->activateListing);
		$listing->addActiveProperty($this->activateListing);
	}
	function defineListingActivationDate($listing)
	{
		if (empty($this->activationDate))
		{
			$this->activationDate = $this->i18n->getDate(date("Y-m-d"));
		}
		$listing->addActivationDateProperty($this->activationDate);
		$listing->addFirstActivationDateProperty($this->activationDate);
	}
	function defineListingExpirationDate($listing)
	{
		$expDateTimestamp = strtotime($this->i18n->getInput('date', $this->activationDate).' + '.$this->packageInfo['listing_lifetime'].' days');
		$expirationDate = $this->i18n->getDate(date("Y-m-d", $expDateTimestamp));
		$listing->addExpirationDateProperty($expirationDate);
	}
	function unsetFieldsClosedForImport($listingData)
	{
		$closedFields = array('sid', 'active', 'activation_date', 'expiration_date', 'views');
		foreach ($closedFields as $field)
		{
			unset($listingData[$field]);
		}
		return $listingData;
	}

	public function init()
	{
	}

	function getConverted($data)
	{
		$listingData = $this->arrayCombiner->combine($this->fieldsScheme, $data);
		$listingData = $this->unsetFieldsClosedForImport($listingData);
		$importedListing = $this->importedListingCreator->createImportedListing($listingData, $this->getListingCategorySid($listingData));

		$listing = $importedListing->getListing();
		$this->setMultilistValues($listing, $listingData);

		$this->defineListingOwner($listing, $listingData);
		$this->defineListingPackageInfo($listing);
		if ($this->activateListing)
		{
			$this->defineListingActiveStatus($listing);
			$this->defineListingActivationDate($listing);
			$this->defineListingExpirationDate($listing);
		}
		$this->setFeaturesIncludeByDefault($listing);

		$propertiesToDelete = array('sid', 'type', 'category_sid', 'listing_package', 'id', 'moderation_status', 'views', 'package', 'pictures', 'user_sid', 'user', 'username', 'keywords');
		$propertiesToDelete = array_merge($propertiesToDelete, $this->videoFieldsIds, $this->fileFieldsIds);

		array_map(array($listing, 'deleteProperty'), $propertiesToDelete);
		return $importedListing;
	}

	protected function getListingCategorySid($listingData)
	{
		$categorySid = null;
		if (!empty($listingData['category']))
			$categorySid = $this->categoryManager->getCategorySIDByID($listingData['category']);
		if (empty($categorySid))
			$categorySid = $this->categorySid;
		return $categorySid;
	}
	
	protected function setFeaturesIncludeByDefault($listing)
	{
		$features = $this->config->getIncludedFeaturesList();
		foreach ($features as $feature)
		{
			\App()->ListingFeaturesManager->setListingFeatureOn($listing, $feature);
		}
	}

	public function setMapper($mapper)
	{
		$this->mapper = $mapper;
	}

	public function setDefaultUserSid($defaultUserSid)
	{
		$this->defaultUserSid = $defaultUserSid;
	}

	public function setFeedSid($feedSid)
	{
		$this->feedSid = $feedSid;
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}
	public function setLogger(){}
	public function setCollectionSaver(){}

	/**
	 * @param \modules\classifieds\lib\Listing\Listing $listing
	 * @param $listingData
	 */
	private function setMultilistValues($listing, $listingData)
	{
		/**
		 * @var \lib\ORM\ObjectProperty[] $multilistProperties
		 */

		$multilistProperties = array_filter($listing->getProperties(), function ($property)
		{
			/**
			 * @var \lib\ORM\ObjectProperty $property
			 */
			return $property->getType() == 'multilist';
		});

		foreach ($multilistProperties as $property)
		{
			$listing->setPropertyValue(
					$property->getID(),
					$this->listingFieldMultiListItemManager->getValueAsArrayFromText(
							$this->getFieldSidByFieldId($property->getID()),
							$listingData[$property->getID()],
							",",
							$this->config->getAddListValues()
					)
			);
		}

	}

	private function getFieldSidByFieldId($fieldId)
	{
		if (!isset($this->fieldSidsById[$fieldId]))
		{
			$this->fieldSidsById[$fieldId] = \App()->ListingFieldManager->getFieldSidById($fieldId);
		}
		return $this->fieldSidsById[$fieldId];
	}
}
