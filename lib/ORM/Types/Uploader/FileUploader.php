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

use Codesleeve\Stapler\ORM\StaplerableInterface;

class FileUploader implements StaplerableInterface
{

    public function __construct()
    {
        Stapler::boot();
    }

    /**
     * All of the model's current file attachments.
     *
     * @var array
     */
    protected $attachedFiles = [];
    protected $attribute;
    protected $key = 0;
    protected $parentKey = 0;
    protected $error = null;

    public function isValid($name)
    {
        is_array($_FILES[$name]['name'])
            ? $this->validateMultipleFIle($name)
            : $this->validateFile($name);

        return is_null($this->error);
    }

    public function upload()
    {
        foreach ($this->attachedFiles as $attachedFile) {
            $attachedFile->afterSave($this);
        }
    }

    public function delete()
    {
        foreach ($this->attachedFiles as $attachedFile) {
            $attachedFile->beforeDelete($this);
            $attachedFile->afterDelete($this);
        }
    }

    protected function validateFile($name)
    {
        if ($_FILES[$name]['error'] != UPLOAD_ERR_OK) {
            $this->error = $this->getUploadedFileErrorCode($_FILES[$name]['error']);
        }
        return $this;
    }

    protected function validateMultipleFIle($name)
    {
        foreach ($_FILES[$name]['name'] as $key => $fileName) {
            if ($_FILES[$name]['error'][$key] != UPLOAD_ERR_OK) {
                $this->error = $this->getUploadedFileErrorCode($_FILES[$name]['error'][$key]);
                break;
            }
        }
        return $this;
    }

    protected function getUploadedFileErrorCode($error)
    {
        $errorCodes = array
        (
            UPLOAD_ERR_INI_SIZE => 'UPLOAD_ERR_INI_SIZE',
            UPLOAD_ERR_FORM_SIZE => 'UPLOAD_ERR_FORM_SIZE',
            UPLOAD_ERR_PARTIAL => 'UPLOAD_ERR_PARTIAL',
            UPLOAD_ERR_NO_FILE => 'UPLOAD_ERR_NO_FILE',
            UPLOAD_ERR_NO_TMP_DIR => 'UPLOAD_ERR_NO_TMP_DIR',
            UPLOAD_ERR_CANT_WRITE => 'UPLOAD_ERR_CANT_WRITE',
            UPLOAD_ERR_EXTENSION => 'UPLOAD_ERR_EXTENSION',
        );
        return isset($errorCodes[$error]) ? $errorCodes[$error] : 'UPLOAD_ERR_UNDEFINED';
    }

    public function fill($data)
    {
        foreach ($data as $key => $name) {
            $this->attribute[$key] = $name;
        }
        return $this;
    }

    /**
     * @return int
     */
    public function getKey()
    {
        return $this->key;
    }

    public function getFiles($attachmentName)
    {
        $files = [];
        if (isset($this->attachedFiles[$attachmentName])) {
            $attachment = $this->attachedFiles[$attachmentName];
            foreach ($attachment->styles as $style) {
                $files[$style->name] = [
                    'url' => $attachment->url($style->name),
                    'name' => isset($this->attribute[$attachmentName . '_file_name']) ? $this->attribute[$attachmentName . '_file_name'] : '',
                ];
                $files['depricated'][] = [$style->name . '_url' => $files[$style->name]['url']]; //for compatibility api v2
            }
        }
        return $files;
    }

    /**
     * @param int $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Accessor method for the $attachedFiles property.
     *
     * @return array
     */
    public function getAttachedFiles()
    {
        return $this->attachedFiles;
    }

    /**
     * Add a new file attachment type to the list of available attachments.
     * This function acts as a quasi constructor for this trait.
     *
     * @param string $name
     * @param array $options
     */
    public function hasAttachedFile($name, array $options = [])
    {
        $attachment = AttachmentFactory::create($name, $options);
        $attachment->setInstance($this);
        $this->attachedFiles[$name] = $attachment;
    }

    /**
     * Handle the dynamic retrieval of attachment objects.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->attachedFiles)) {
            return $this->attachedFiles[$key];
        }

        return isset($this->attribute[$key]) ? $this->attribute[$key] : null;
    }

    /**
     * Handle the dynamic setting of attachment objects.
     *
     * @param string $key
     * @param mixed $value
     */
    public function setAttribute($key, $value)
    {
        if (array_key_exists($key, $this->attachedFiles)) {
            if ($value) {
                $attachedFile = $this->attachedFiles[$key];
                $attachedFile->setUploadedFile($value);
            }

            return;
        }
        $this->attribute[$key] = $value;
    }

    /**
     * Return the image paths (across all styles) for a given attachment.
     *
     * @param  string $attachmentName
     * @return array
     */
    public function pathsForAttachment($attachmentName)
    {
        $paths = [];

        if (isset($this->attachedFiles[$attachmentName])) {
            $attachment = $this->attachedFiles[$attachmentName];

            foreach ($attachment->styles as $style) {
                $paths[$style->name] = $attachment->path($style->name);
            }
        }

        return $paths;
    }

    /**
     * Return the image urls (across all styles) for a given attachment.
     *
     * @param  string $attachmentName
     * @return array
     */
    public function urlsForAttachment($attachmentName)
    {
        $urls = [];

        if (isset($this->attachedFiles[$attachmentName])) {
            $attachment = $this->attachedFiles[$attachmentName];

            foreach ($attachment->styles as $style) {
                $urls[$style->name] = $attachment->url($style->name);
            }
        }

        return $urls;
    }

    /**
     * @return string | null
     */
    public function getError()
    {
        return $this->error;
    }

    public function clearAttr()
    {
        $this->attribute = [];
    }

    /**
     * @return int
     */
    public function getParentKey()
    {
        return $this->parentKey;
    }

    /**
     * @param int $parentKey
     */
    public function setParentKey($parentKey)
    {
        $this->parentKey = $parentKey;
        return $this;
    }
}
