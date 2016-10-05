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

class YoutubeFilesStackManager
{

    public function addFileToStack($fileSid, $listingSid)
    {
        \App()->DB->query("DELETE FROM `field_types_youtube_files_stack` WHERE `uploaded_file_sid` = ?n", $fileSid);
        return \App()->DB->query("INSERT INTO `field_types_youtube_files_stack`(`uploaded_file_sid`, `listing_sid`) VALUES(?n, ?n)", $fileSid, $listingSid);
    }

    public function deleteFileFromStack($fileSid)
    {
        return \App()->DB->query("DELETE FROM `field_types_youtube_files_stack` WHERE `uploaded_file_sid` = ?n", $fileSid);
    }

    public function gelAllFilesStack()
    {
        return \App()->DB->query("SELECT * FROM `field_types_youtube_files_stack`");
    }
}
