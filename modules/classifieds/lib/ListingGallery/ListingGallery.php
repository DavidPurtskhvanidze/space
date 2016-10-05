<?php
/**
 *
 *    Module: classifieds v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: classifieds-7.5.0-1
 *    Tag: tags/7.5.0-1@19773, 2016-06-17 13:19:01
 *
 *    This file is part of the 'classifieds' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\classifieds\lib\ListingGallery;

use core\ExtensionPoint;
use modules\classifieds\lib\Listing\Listing;

class ListingGallery
{
    /**
     * @var Listing
     */
    protected $listing;
	protected $listing_sid;
    protected $listingLastUploadedImageSid;
    protected $error;
    protected $upload_files_directory;
    protected $WatermarkerFactory;
    protected $siteUrl;
    protected $DB;
    protected $listing_picture_storage_method;
    /**
     * @var \modules\miscellaneous\lib\image\IImageResourceProcessor
     */
    protected $imageResourceProcessor = null;

    protected $styles = array();

	public function setUploadFilesDirectory($d){ $this->upload_files_directory = $d; }
	public function setWatermarkerFactory($x){$this->WatermarkerFactory = $x;}
	public function setSiteUrl($x){$this->siteUrl = $x;}
	public function setDB($x){$this->DB = $x;}

    public function setStyles($styles)
    {
        $this->styles = $styles;
    }

	public function setListingPictureStorageMethod($listing_picture_storage_method)
	{
		$this->listing_picture_storage_method = $listing_picture_storage_method;
	}
	public function setJpegImageQuality($jpeg_image_quality)
	{
		$this->jpeg_image_quality = $jpeg_image_quality;
	}
		
	function setListingSID($listing_sid)
	{
		$this->listing_sid = $listing_sid;
	}

	
	function updatePictureCaption($picture_sid, $picture_caption)
	{
		$this->DB->query("UPDATE `classifieds_listings_pictures` SET `caption` = ?s WHERE `sid` = ?n", $picture_caption, $picture_sid);
	}
	
	function getPicturesAmount()
	{
		$this->DB->resetCacheForquery("SELECT COUNT(*) FROM `classifieds_listings_pictures` WHERE listing_sid = ?n", $this->listing_sid);
		$count = $this->DB->getSingleValue("SELECT COUNT(*) FROM `classifieds_listings_pictures` WHERE listing_sid = ?n", $this->listing_sid);
		if (empty($count)) return 0;
		return $count;
	}
	
	function deleteImageBySID($image_sid, $attachementName = 'pictures')
	{
		$canPerform = true;
		$validators = new ExtensionPoint('modules\classifieds\apps\AdminPanel\IDeleteListingPictureValidator');
		foreach ($validators as $validator)
		{
			$validator->setPictureSid($image_sid);
			$canPerform &= $validator->isValid();
		}
		if (!$canPerform) return;
        $pictureProperty = $this->listing->getProperty($attachementName);
        $pictureProperty->type->delete($image_sid);
		$this->setListingPictureAmount($this->getPicturesAmount());
	}

	function setListingPictureAmount($pictures_amount)
	{
		$this->DB->query("UPDATE `classifieds_listings` SET `pictures` = ?n WHERE `sid` = ?n", $pictures_amount, $this->listing_sid);
	}
	
	function deleteImages()
	{
		$images_info = $this->DB->query("SELECT sid FROM `classifieds_listings_pictures` WHERE `listing_sid` = ?n", $this->listing_sid);
		foreach ($images_info as $image_info) $this->deleteImageBySID($image_info['sid']);
        return true;
	}


    /**
     * @param $ImageResourceProcessor
     */
    public function setImageResourceProcessor($ImageResourceProcessor)
    {
        $this->imageResourceProcessor= $ImageResourceProcessor;
    }


	public function addRemoteImageToListing($imageUrl, $imageCaption)
	{
		$this->DB->query(
			"INSERT INTO `classifieds_listings_pictures` SET `listing_sid` = ?n, `storage_method` = ?s, `picture_url` = ?s, `caption` = ?s, `order` = ?n",
			$this->listing_sid, "url", $imageUrl, $imageCaption, $this->getMaxPictureOrder() + 1
		);
		$this->setListingPictureAmount($this->getPicturesAmount());
		return true;
	}

	private function getMaxPictureOrder()
	{
		$this->DB->resetCacheForquery("SELECT MAX(`order`) FROM `classifieds_listings_pictures` WHERE `listing_sid` = ?n", $this->listing_sid);
		$maxOrder = $this->DB->getSingleValue("SELECT MAX(`order`) FROM `classifieds_listings_pictures` WHERE `listing_sid` = ?n", $this->listing_sid);
		return (empty($maxOrder) ? 0 : $maxOrder);
	}

	function getImageResourceContent($image_resource)
	{
		ob_start();
		$this->imageResourceProcessor->imageResourceToJpeg($image_resource);
		$image_content = ob_get_contents();
		ob_end_clean();
		return $image_content;
	}


	function getError()
	{
		return $this->error;
	}

	public function getListingLastUploadedImageSid()
	{
		return $this->listingLastUploadedImageSid;
	}

	public function setListingLastUploadedImageSid($listingLastUploadedImageSid)
	{
		$this->listingLastUploadedImageSid = $listingLastUploadedImageSid;
	}

	public function setPicturesOrder($picturesSorted)
	{
		// leave only pictures that owned by listing
		$picturesSorted = array_intersect($picturesSorted, $this->getPictureSids());
		$order = 1;
		foreach ($picturesSorted as $picturesSid)
		{
			\App()->DB->query('UPDATE `classifieds_listings_pictures` SET `order` = ?n WHERE `sid` = ?n', $order++, $picturesSid);
		}
	}

	private function getPictureSids()
	{
		return \App()->DB->column("SELECT `sid` FROM `classifieds_listings_pictures` WHERE `listing_sid` = ?n", $this->listing_sid);
	}

	public function getListingSidByPictureSid($pictureSid)
	{
		return \App()->DB->getSingleValue("SELECT `listing_sid` FROM `classifieds_listings_pictures` WHERE `sid` = ?n", $pictureSid);
	}

	public function uploadImages($images)
	{
		$uploadedImagesCount = 0;
		if ($this->listing_picture_storage_method == 'url') {
            $query = "INSERT INTO `classifieds_listings_pictures`(`listing_sid`, `storage_method`, `picture_url`, `order`) VALUES";
            $params = array();
            $order = 1;

            foreach ($images as $imageUrl) {
                if ($order > 1) {
                    $query .= ", ";
                }
                $query .= "({$this->listing_sid}, 'url', ?s, ?n)";

                $params[] = $imageUrl;
                $params[] = $order++;
            }

            array_unshift($params, $query);
            call_user_func_array(array(\App()->DB, 'query'), $params);

            $uploadedImagesCount = count($images);
        }
        else
        {
            foreach ($images as $imageUrl)
            {
                if (@fopen($imageUrl, "r") == true)
                {
                    $this->listing->getProperty('pictures')->type->attacheFile($imageUrl);
                    $uploadedImagesCount++;
                }
                else
                {
                    \App()->WarningMessages->addMessage('NO_IMAGE_FILE', array('fileName' => $imageUrl));
                }
            }

        }

		return $uploadedImagesCount;
	}

    /**
     * @return Listing
     */
    public function getListing()
    {
        return $this->listing;
    }

    /**
     * @param Listing $listing
     * @return $this
     */
    public function setListing($listing)
    {
        $this->listing = $listing;
        return $this;
    }
}
