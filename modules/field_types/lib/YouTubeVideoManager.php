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

class YouTubeVideoManager
{
    private $googleClient;

    private $definedAccessToken;

    public function __construct()
    {
        require_once PATH_TO_ROOT . \App()->SystemSettings['VendorLibs'] . 'google-api/Google/autoload.php';

        $client_id = \App()->SettingsFromDB->getSettingByName('google_client_id');
        $client_secret = \App()->SettingsFromDB->getSettingByName('google_client_secret');
        $application_name = \App()->SettingsFromDB->getSettingByName('google_app_name');
        $scope = array('https://www.googleapis.com/auth/youtube.upload', 'https://www.googleapis.com/auth/youtube', 'https://www.googleapis.com/auth/youtubepartner');

        // Client init
        $client = new \Google_Client();
        $client->setApplicationName($application_name);
        $client->setClientId($client_id);
        $client->setAccessType('offline');
        $client->setScopes($scope);
        $client->setClientSecret($client_secret);
        $this->googleClient = $client;
        $this->definedAccessToken = \App()->DB->getSingleValue('SELECT `token` FROM `field_types_token`');

    }

    public function refreshTokenIfExpired()
    {
        $this->googleClient->setAccessToken($this->getDefinedAccessToken());

        if ($this->googleClient->getAccessToken())
        {
            /**
             * Check to see if our access token has expired. If so, get a new one and save it to file for future use.
             */
            if($this->googleClient->isAccessTokenExpired())
            {
                $newToken = json_decode($this->googleClient->getAccessToken());
                $this->googleClient->refreshToken($newToken->refresh_token);
                $this->saveToken($this->googleClient->getAccessToken());
            }
        }
        else
        {
            throw new Exception('Problems creating the google client');
        }
    }

    public function upload($videoPath, $title, $description, $category, $tags)
    {
        $this->refreshTokenIfExpired();
        $youtube = new \Google_Service_YouTube($this->googleClient);
        // Create a snipet with title, description, tags and category id
        $snippet = new \Google_Service_YouTube_VideoSnippet();
        $snippet->setTitle($title);
        $snippet->setDescription($description);
        $snippet->setCategoryId($category);
        $snippet->setTags($tags);

        // Create a video status with privacy status. Options are "public", "private" and "unlisted".
        $status = new \Google_Service_YouTube_VideoStatus();
        $status->setPrivacyStatus('public');

        // Create a YouTube video with snippet and status
        $video = new \Google_Service_YouTube_Video();
        $video->setSnippet($snippet);
        $video->setStatus($status);

        // Size of each chunk of data in bytes. Setting it higher leads faster upload (less chunks,
        // for reliable connections). Setting it lower leads better recovery (fine-grained chunks)
        $chunkSizeBytes = 1 * 1024 * 1024;

        // Setting the defer flag to true tells the client to return a request which can be called
        // with ->execute(); instead of making the API call immediately.
        $this->googleClient->setDefer(true);
        // Create a request for the API's videos.insert method to create and upload the video.
        $insertRequest = $youtube->videos->insert("status,snippet", $video);

        // Create a MediaFileUpload object for resumable uploads.
        $media = new \Google_Http_MediaFileUpload(
            $this->googleClient,
            $insertRequest,
            'video/*',
            null,
            true,
            $chunkSizeBytes
        );
        $media->setFileSize(filesize($videoPath));

        // Read the media file and upload it chunk by chunk.
        $status = false;
        $handle = fopen($videoPath, "rb");
        while (!$status && !feof($handle)) {
            $chunk = fread($handle, $chunkSizeBytes);
            $status = $media->nextChunk($chunk);
        }

        fclose($handle);

        /**
         * Video has successfully been upload, now lets perform some cleanup functions for this video
         */
        if ($status->status['uploadStatus'] == 'uploaded')
        {
            return $status;
        }

        // If you want to make other calls after the file upload, set setDefer back to false
        $this->googleClient->setDefer(true);

        return false;
    }

    public function delete($videoId)
    {
        $this->refreshTokenIfExpired();
        $youtube = new \Google_Service_YouTube($this->googleClient);
        $youtube->videos->delete($videoId);
        return true;
    }

    public function getDefinedAccessToken()
    {
        return $this->definedAccessToken;
    }

    public function getClient()
    {
        return $this->googleClient;
    }

    public function isTokenDefined()
    {
        return !empty($this->definedAccessToken);
    }

    public function saveToken($token)
    {
        \App()->DB->query("DELETE FROM `field_types_token`");
        \App()->DB->query("INSERT INTO `field_types_token`(`token`) VALUES (?s)", $token);
    }
}
