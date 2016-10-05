<?php
/**
 *
 *    iLister v.7.5.0, (c) WorksForWeb 2005 - 2016
 *
 *    Package: iLister-7.5.0
 *    Tag: modular/projects/classifieds/tags/7.5.0@19890, 2016-06-17 13:38:22
 *
 *    This file is a part of iLister software
 *    and is protected by international copyright laws
 *
 */


namespace lib\File;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class Upload extends UploadedFile
{
    /**
     * Moves the file to a new location.
     *
     * @param string $directory The destination folder
     * @param string $name      The new file name
     *
     * @return \SplFileInfo A File object representing the new file
     *
     * @throws \lib\File\FileException if, for any reason, the file could not have been moved
     */
    public function move($directory, $name = null)
    {
        try
        {
          return  parent::move($directory, $name);
        }
        catch(\Symfony\Component\HttpFoundation\File\Exception\FileException $e)
        {
            throw new \lib\File\FileException($e->getMessage());
        }
    }
}
