<?php
/**
 *
 *    Module: field_types v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: field_types-7.5.0-1
 *    Tag: tags/7.5.0-1@19782, 2016-06-17 13:19:23
 *
 *    This file is part of the 'field_types' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\field_types\lib;

class VideoUploadToYoutubeTask extends \modules\miscellaneous\lib\ScheduledTaskBase
{
    /**
     * @var \modules\field_types\lib\YoutubeVideoManager
     */
    private $youTubeVideoManager;

    public static function getOrder()
	{
		return 900;
	}

    private function init()
    {
        try
        {
            $this->youTubeVideoManager = new \modules\field_types\lib\YouTubeVideoManager();
        }
        catch(\modules\field_types\lib\Exception $e)
        {
            $this->scheduler->log($e->getMessage());
        }
        catch(\Exception $e)
        {
            $this->scheduler->log("Caught Google service Exception ".$e->getCode(). " message is ".$e->getMessage());
        }
    }

	public function run()
	{
        $this->scheduler->log('Upload Video To YouTube');
        $this->init();
        if( ! $this->youTubeVideoManager->isTokenDefined())
        {
            $this->scheduler->log('Access Token is not defined');
            return false;
        }

        $files = \App()->DB->query("SELECT * FROM `field_types_youtube_files_stack`");

		$this->scheduler->log(sprintf('Found %d video.' , count($files)));
        $category = \App()->SettingsFromDB->getSettingByName('youtube_category');
        foreach($files as $file)
        {
            $listing = \App()->ListingManager->getListingBySid($file['listing_sid']);
            $listingDisplayer = new \modules\classifieds\lib\Listing\ListingDisplayer();
            $listingDisplayer->setObjectToArrayAdapterFactory(\App()->ObjectToArrayAdapterFactory);
            $listingWraped = $listingDisplayer->wrapListing($listing);
            if (!empty($listingWraped['Comments']))
            {
                $description = strip_tags($listingWraped['Comments']);
            }
            elseif (!empty($listingWraped['Description']))
            {
                $description = strip_tags($listingWraped['Description']);
            }
            elseif (!empty($listingWraped['SellerComments']))
            {
                $description = strip_tags($listingWraped['SellerComments']);
            }
            else
            {
                $description = strip_tags((string) $listingWraped);
            }

            $this->uploadToYoutube($file['uploaded_file_sid'], strip_tags((string) $listingWraped), $description, $category, $listing->getKeywords());
        }
	}

    private function uploadToYoutube($fileSid, $title, $description, $category, $tags)
    {
        try
        {
            $videoPath =  \App()->UploadFileManager->getUploadedFilePath($fileSid);
            $uploadStatus = $this->youTubeVideoManager->upload($videoPath, $title, $description, $category, $tags);
            if ($uploadStatus !== false)
            {
                \App()->UploadFileManager->updateUploadedFileYouTube($fileSid, $uploadStatus->id);
                $this->scheduler->log('Uploaded file name in YouTube: ' . $uploadStatus->id);
            }
            else
            {
                $this->scheduler->log("Not known error when downloading videos on service");
            }
        }
        catch(\Google_Service_Exception $e)
        {
            $this->scheduler->log("Caught Google service Exception " . $e->getCode() . " message is " . $e->getMessage());
        }
        catch(\Exception $e)
        {
            $this->scheduler->log($e->getMessage());
        }

    }
}
