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

namespace lib\ORM\Types\Uploader;


class PictureUploader extends FileUploader
{

    protected function validateMultipleFIle($name)
    {
        parent::validateMultipleFIle($name);
        if (is_null($this->error))
        {
            foreach ($_FILES[$name]['name'] as $key => $fileName) {
                if (!$this->isValidateType($_FILES[$name]['tmp_name'][$key])) {
                    break;
                }
            }

        }
        return $this;
    }

    protected function validateFile($name)
    {
        parent::validateFile($name);
        $this->isValidateType($_FILES[$name]['tmp_name']);
        return $this;
    }

    private function isValidateType($fileName)
    {
        $imageInfo = getimagesize($fileName);
        if ( !($imageInfo['2'] >= 1 && $imageInfo['2'] <= 3) )
        {
            $this->error = 'NOT_SUPPORTED_IMAGE_FORMAT';
            return false;
        }
        return true;
    }

    public function upload()
    {
        try
        {
            foreach ($this->attachedFiles as $attachedFile) {
                $attachedFile->afterSave($this);
            }
        }
        catch (PictureUploadException $e)
        {
            $this->error = $e->getMessage();
            throw $e;
        }
    }
}
