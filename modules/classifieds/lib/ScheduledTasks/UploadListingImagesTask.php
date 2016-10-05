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


namespace modules\classifieds\lib\ScheduledTasks;

class UploadListingImagesTask //extends \modules\miscellaneous\lib\ScheduledTaskBase // executed separately
{
	const PICTURES_LIMIT = 2;

	private $showLog;

	public function run()
	{
		$listingGallery = \App()->ListingGalleryManager->createListingGallery();

		$picturesInfo = \App()->DB->query("SELECT `sid`, `picture_url` FROM `classifieds_listings_pictures` WHERE `storage_method` = 'url' LIMIT ?n", self::PICTURES_LIMIT);

		if ($this->showLog)
		{
			echo "<pre>\n";
			foreach ($picturesInfo as $pictureInfo)
			{

				echo 'Uploading image from ' . $pictureInfo['picture_url'];
				if ($listingGallery->uploadExistingImage($pictureInfo['sid'], $pictureInfo['picture_url']))
					echo " ...OK\n";
				else
					echo " ...failed\n";
			}
			echo "\n</pre>";
		}
		else
		{
			foreach ($picturesInfo as $pictureInfo)
			{
				$listingGallery->uploadExistingImage($pictureInfo['sid'], $pictureInfo['picture_url']);
			}
		}
	}

	public function setShowLog($showLog)
	{
		$this->showLog = $showLog;
	}
}
