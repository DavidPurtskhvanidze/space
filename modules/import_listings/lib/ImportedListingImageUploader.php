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

class ImportedListingImageUploader
{
	var $importFilesDir;
	var $imagesFieldName = 'pictures';
	var $imagesDelimiter = ';';

	/**
	 * @var \modules\classifieds\lib\ListingGallery\ListingGallery
	 */
	var $listingGallery;

	/**
	 * @var ImportLogger
	 */
	private $logger;

	/**
	 * @var ListingCollectionSaver
	 */
	private $collectionSaver;

	function setImportFilesDir($importFilesDir)
	{
		$this->importFilesDir = $importFilesDir;
	}
	function setListingGallery($listingGallery)
	{
		$this->listingGallery = $listingGallery;
	}

	/**
	 * @param \modules\classifieds\lib\Listing\Listing $listing
	 * @param $listingData
	 */
	function processImages($listing, $listingData)
	{
		if (!empty($listingData[$this->imagesFieldName]))
		{
			$images = explode($this->imagesDelimiter, $listingData[$this->imagesFieldName]);
			$images = array_map('trim', $images);
			$images = array_filter($images, function ($v) {
				return !empty($v);
			});

			$images = array_map([$this, 'getImageAbsolutePath'], $images);
            if (!is_null($listing->getSID()))
            {
                $this->listingGallery->setListing($listing);
                $this->listingGallery->setListingSID($listing->getSID());
                $this->listingGallery->deleteImages();
                $uploadedImagesCount = $this->listingGallery->uploadImages($images);
                $this->listingGallery->setListingPictureAmount($uploadedImagesCount);
            }
            else // Grouped listing creation
            {
                array_map([$this->collectionSaver, 'addPictureToLastListing'], $images);
				$uploadedImagesCount = count($images);
			}

			$this->logger->logPictureAdd($uploadedImagesCount);
		}
	}

	public function setLogger($logger)
	{
		$this->logger = $logger;
	}

	public function setCollectionSaver($collectionSaver)
	{
		$this->collectionSaver = $collectionSaver;
	}

	private function getImageAbsolutePath($imagePath)
	{
		$url = parse_url($imagePath);
		return isset($url['scheme']) || file_exists($imagePath) ? $imagePath : \App()->Path->combine($this->importFilesDir, $imagePath);
	}
}
