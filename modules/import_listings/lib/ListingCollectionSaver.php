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

class ListingCollectionSaver
{
	private $listingsLimit = 10;

	private $lastListing = 0;
	private $listings = array();
	private $listingPackages = array();
	private $options = array();
	private $listingOptionColumns = array();
	private $pictures = array();

	private $listingsColumns = array('pictures');

	private $picturesColumns = array('listing_sid', 'storage_method', 'picture_url', 'order');
	private $optionsColumns = array('id', 'caption', 'category_sid', 'order', 'type', 'is_required');
	private $listingPackagesColumns = array('listing_sid', 'package_sid', 'package_info');

	public function addListingToSave($listing)
	{
		if (count($this->listings) >= $this->listingsLimit)
		{
			$this->saveListingsToDB();
		}
		$this->lastListing++;

		$propertiesSqlValues = \App()->ObjectDBManager->getObjectPropertiesSqlValues($listing);
		$propertiesSqlValues['category_sid'] = $listing->getCategorySID();
		$propertiesSqlValues['user_sid'] = $listing->getUserSID();
		$keywords = \App()->DB->real_escape_string($listing->getKeywords());
		$propertiesSqlValues['keywords'] = "'{$keywords}'";

		$this->listingsColumns = array_unique(array_merge(array_keys($propertiesSqlValues), $this->listingsColumns));
		$this->listings[$this->lastListing] = $propertiesSqlValues;

		$packageInfo = $listing->getListingPackageInfo();
		$this->listingPackages[$this->lastListing] = array
		(
			'package_sid' => $packageInfo['package_sid'],
			'package_info' => "'" . serialize($packageInfo) . "'"
		);
	}

	public function addPictureToLastListing($url)
	{
		$this->pictures[$this->lastListing][] = $url;
	}

	public function addOptionToSave($categorySid, $id, $caption)
	{
		$this->listingOptionColumns[] = $id;

		$sqlValues = array
		(
			'id' => $id,
			'caption' => $caption,
			'category_sid' => $categorySid,
			'type' => 'boolean',
			'is_required' => '0',
		);
		$this->options[] = $sqlValues;
	}

	public function saveListingsToDB()
	{
		if (!empty($this->listings))
		{
			$this->saveOptionsToDB();

			foreach ($this->listings as $key => &$values)
			{
				if (isset($this->pictures[$key])) $values['pictures'] = count($this->pictures[$key]);
			}
			$firstInsertedListingSid = $this->performMultipleInsertAndReturnFirstInsertedSid('classifieds_listings', $this->listingsColumns, $this->listings);

			$listingSids = array();
			foreach ($this->listings as $key => $value)
			{
				$listingSids[$key] = $firstInsertedListingSid + $key - 1;
			}
			$this->savePicturesToDB($listingSids);
			$this->saveListingPackagesToDB($listingSids);

			$this->listings = array();
			$this->listingsColumns = array('pictures');
			$this->lastListing = 0;
		}
	}

	private function savePicturesToDB($listingSids)
	{
		if (!empty($this->pictures))
		{
			$values = [];
			foreach ($this->pictures as $key => $urls)
			{
				foreach ($urls as $order => $url)
				{
					$url = \App()->DB->real_escape_string($url);
					$aaa = [$listingSids[$key], "'url'", "'$url'", $order + 1];
					$values[] = array_combine($this->picturesColumns, $aaa);
				}
			}

			$this->performMultipleInsertAndReturnFirstInsertedSid('classifieds_listings_pictures', $this->picturesColumns, $values);

			$this->pictures = [];
		}
	}

	private function saveListingPackagesToDB($listingSids)
	{
		if (!empty($this->listingPackages))
		{
			foreach ($this->listingPackages as $key => &$listingPackage)
			{
				$listingPackage['listing_sid'] = $listingSids[$key];
			}

			$this->performMultipleInsertAndReturnFirstInsertedSid('membership_plan_listing_packages', $this->listingPackagesColumns, $this->listingPackages);

			$this->listingPackages = [];
		}
	}

	public function saveOptionsToDB()
	{
		if (!empty($this->options))
		{
			$categorySid = $this->options[0]['category_sid'];

			foreach ($this->options as &$option)
			{
				$listing_field = \App()->ListingFieldManager->createListingField($option, $categorySid);
				\App()->ListingFieldManager->addColumnToListingTableForField($listing_field);
				\App()->ListingFieldManager->saveListingField($listing_field);
				\App()->ListingFieldManager->addListingFieldToOrderTable($listing_field);
			}

			$this->options = array();

			\App()->MemoryCache->reset('getListingFieldsInfoByCategory_' . $categorySid);
			\App()->MemoryCache->reset('cache for classifieds_listing_fields');
			\App()->MemoryCache->reset('cache for classifieds_listing_field_list');
			\App()->MemoryCache->reset('cache for classifieds_listing_field_tree');
			\App()->MemoryCache->reset('DB_Q_SELECT * FROM classifieds_listing_fields');
		}
	}

	private function performMultipleInsertAndReturnFirstInsertedSid($table, $columns, $values)
	{
		$valuesToInsert = array();
		foreach($values as $rowValues)
		{
			$row = array();
			foreach($columns as $column)
			{
				$row[] = isset($rowValues[$column]) && !is_null($rowValues[$column]) ? $rowValues[$column] : 'NULL';
			}
			$valuesToInsert[] = '(' . join(',', $row) . ')';
		}

		$preWrappedColumns = array_map(function($column) {return "`$column`";}, $columns);
        $wrappedColumns = join(',', $preWrappedColumns);
        $insertValues = join(',', $valuesToInsert);
		return \App()->DB->queryNoReplace("INSERT INTO `" . $table . "` (" . $wrappedColumns . ") VALUES " . $insertValues);
	}

	private function saveListingOptionColumns()
	{
		if (!empty($this->listingOptionColumns))
		{
			$addColumnStatements = array();
			foreach ($this->listingOptionColumns as $columnName)
			{
				$addColumnStatements[] = "ADD COLUMN `$columnName` BOOLEAN";
			}
			\App()->DB->query("ALTER TABLE `classifieds_listings` " . join(',', $addColumnStatements));
			$this->listingOptionColumns = array();
		}
	}

	public function setListingsLimit($listingsLimit)
	{
		$this->listingsLimit = $listingsLimit;
	}
}
