<?php

namespace lib\ORM\Types;

use lib\ORM\Types\Uploader\PictureUploader;

class PicturesType extends Type
{
    /**
     * @var PictureUploader;
     */
    private $pictureUploader;

    function __construct($property_info)
    {
        $settings = \App()->SystemSettings;
        $dir = $this->object ? $this->object->getTableName() : (isset($property_info['table_alias']) ? $property_info['table_alias'] : 'common');
        $property_info['base_path'] = PATH_TO_ROOT . $settings['PicturesDir'];

        if (!isset($info['path'])) {
            $property_info['path'] = ':app_root/' . $dir . '/:attachment/:parent_id/:id/:style/:filename';
        }

        if (!isset($property_info['url'])) {
            $property_info['url'] = $settings['SiteUrl'] . '/' . PATH_TO_ROOT . $settings['PicturesDir'] . $dir . '/:attachment/:parent_id/:id/:style/:filename';
        }

        parent::__construct($property_info);
        $this->sql_type = 'UNSIGNED';
        $this->default_template = 'pictures.tpl';
        $this->pictureUploader = new PictureUploader();
        $this->pictureUploader->setParentKey($this->object_sid);
        $this->pictureUploader->hasAttachedFile($property_info['id'], $property_info);
    }

    function getPropertyVariablesToAssignTypeSpecific()
    {
        $pictures_info = $this->getPictures();
        return array(
            'pictures' => $pictures_info,
            'number_of_pictures' => count($pictures_info),
        );
    }

    function isEmpty()
    {
        return is_array($_FILES[$this->property_info['id']]['name'])
            ? empty($_FILES[$this->property_info['id']]['tmp_name'][0])
            : empty($_FILES[$this->property_info['id']]['tmp_name']);
    }

    function getValue()
    {
        return $this->getPictures();
    }

    /**
     * upload files & save to table;
     * @return array - uploaded files info
     */
    public function upload()
    {
        $data = [];
        if (is_array($_FILES[$this->property_info['id']]['name'])) {
            $files = $this->reArrayFiles($_FILES[$this->property_info['id']]);
            foreach ($files as $key => $file) {
                $data[] = $this->attacheFile($file);
            }
        } else {
            $data[] = $this->attacheFile($_FILES[$this->property_info['id']]);
        }
        return $data;
    }

    public function attacheFile($file)
    {
        $this->pictureUploader->clearAttr();
        $this->pictureUploader->setParentKey($this->object_sid);
        $sid = $this->insert();
        $this->pictureUploader->setKey($sid);
        $this->pictureUploader->setAttribute($this->property_info['id'], $file);
        $this->pictureUploader->upload();
        $this->update($sid);
        return ['id' => $sid, 'caption' => '', 'file' => $this->pictureUploader->getFiles($this->property_info['id'])];
    }

    /**
     * @return int - last sid
     */
    protected function insert()
    {
        $cKey = $this->property_info['key'];
        return \App()->DB->query("INSERT INTO `{$this->property_info['table']}` (`{$cKey}`) VALUES(?n)", $this->object_sid);
    }

    public function delete($sid)
    {
        $uploader = clone $this->pictureUploader;
        $uploader->clearAttr();
        $info = \App()->DB->getSingleRow("SELECT * FROM `{$this->property_info['table']}` WHERE `sid` = ?n", $sid);
        $uploader->fill($info);
        $uploader->setParentKey($this->object_sid);
        $uploader->setKey($sid);
        $uploader->delete();
        \App()->DB->query("DELETE FROM `{$this->property_info['table']}` WHERE `sid` = ?n", $sid);
    }

    protected $updateActions = [];

    public function afterUpdate($callback)
    {
        $this->updateActions[] = $callback;
    }

    /**
     * @param int $sid
     * @return void
     */
    protected function update($sid)
    {
        $name = $this->property_info['id'];
        $cFileName = $name . '_file_name';
        $cFileSize = $name . '_file_size';
        $cFileType = $name . '_content_type';
        \App()->DB->query(
            "UPDATE `{$this->property_info['table']}`
              SET `{$cFileName}` = ?s,
                  `{$cFileSize}` = ?n,
                  `{$cFileType}` = ?s
              WHERE `sid` = ?n",
            $this->pictureUploader->getAttribute($cFileName), $this->pictureUploader->getAttribute($cFileSize), $this->pictureUploader->getAttribute($cFileType), $sid);

        foreach ($this->updateActions as &$action) {
            if ($action instanceof \Closure) {
                call_user_func_array($action, ['uploader' => $this->pictureUploader]);
            }
        }
    }

    private function reArrayFiles(&$file_post)
    {

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }

    private $picLimit = null;

    public function setPicLimit($limit)
    {
        $this->picLimit = $limit;
        return $this;
    }

    public function isValid()
    {
        $error = '';

        if (!$this->pictureUploader->isValid($this->property_info['id'])) {
            $error = $this->pictureUploader->getError();
        }

        $numberOfPicture = count($this->getPictures());
        $numberOfPictureAllowed = !is_null($this->picLimit) ? $this->picLimit : $this->property_info['limit'];
        $multipleFile = is_array($_FILES[$this->property_info['id']]['name']);

        if ($numberOfPictureAllowed) {
            if ($multipleFile) {
                foreach ($_FILES[$this->property_info['id']]['name'] as $key => $name) {
                    if ($numberOfPicture++ >= $numberOfPictureAllowed) {
                        $error = 'PICTURES_LIMIT_EXCEEDED';
                        break;
                    }
                }
            } else {
                if ($numberOfPicture++ >= $numberOfPictureAllowed) {
                    $error = 'PICTURES_LIMIT_EXCEEDED';
                }
            }
            if (!empty($error)) {
                $this->addValidationError($error);
                return false;
            }
        }

        return true;
    }

    function getDisplayValue()
    {
        $pictures = $this->getPictures();
        if (!empty($this->object_sid)) {
            return array('numberOfItems' => count($pictures), 'collection' => $pictures);
        }
        return $this->property_info['value'];
    }

    public function getSQLValue()
    {
        return count($this->getPictures());
    }

    private $picturesCollection = null;

    protected function getPictures()
    {
        if (is_null($this->picturesCollection)) {
            $attachments = !empty($this->property_info['attachments'])
                ? $this->property_info['attachments']
                : \App()->DB->query("SELECT * FROM `{$this->property_info['table']}` WHERE `{$this->property_info['key']}` = ?n", $this->object_sid);
            $this->pictureUploader->setParentKey($this->object_sid);

            foreach ($attachments as $attachment) {
                if ($attachment['storage_method'] != 'url') {
                    $fileUploader = clone $this->pictureUploader;
                    $fileUploader->fill($attachment);
                    $fileUploader->setKey($attachment['sid']);
                    $fileUploader->hasAttachedFile($this->property_info['id'], $this->property_info);
                    $files = $fileUploader->getFiles($this->property_info['id']);
                    $picInfo = [
                        'id' => $attachment['sid'],
                        'storage_method' => 'file_system',
                        'caption' => $attachment['caption'],
                        'file' => $files,
                        'picture_url' => $files['picture']['url'],
                        'thumbnail_url' => $files['thumbnail']['url'],
                        'order' => $attachment['order'],
                    ];
                    $picInfo = array_merge($picInfo, $files['depricated']); //for compatibility api v2
                    $this->picturesCollection[] = $picInfo;
                } else {
                    $this->picturesCollection[] = ['id' => $attachment['sid'], 'storage_method' => 'url', 'caption' => $attachment['caption'], 'file' => ['name' => $attachment['picture_url'], 'url' => $attachment['picture_url']]];
                }

            }
        }
        return $this->picturesCollection;
    }
}
