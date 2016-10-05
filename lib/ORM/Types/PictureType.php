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


namespace lib\ORM\Types;


use lib\ORM\Types\Uploader\PictureUploader;
use lib\ORM\Types\Uploader\PropertyInfo;

class PictureType extends Type
{
    use PropertyInfo;
    /**
     * @var PictureUploader
     */
    private $pictureUploader;
    private $deleted = false;

    function __construct($property_info)
    {
        $property_info = $this->normalizePropertyInfo($property_info);
        parent::__construct($property_info);
        $this->pictureUploader = new PictureUploader();
        $this->pictureUploader->setKey($this->object_sid);
        $this->pictureUploader->hasAttachedFile($property_info['id'], $property_info);
        $this->default_template = 'picture.tpl';
    }

    private function fillUploaderData()
    {
        $this->pictureUploader->clearAttr();
        $this->pictureUploader->fill($this->object->getDetails()->getData());
        $this->pictureUploader->setKey($this->object_sid);
    }

    public function delete()
    {
        $this->fillUploaderData();
        $this->pictureUploader->delete();
        $this->pictureUploader->clearAttr();
        $this->deleted = true;
    }

    public function isValid()
    {
        $this->fillUploaderData();

        $res = ((!$this->isEmpty() && $this->property_info['is_required'] != '0') || !$this->isEmpty())
            ? $this->pictureUploader->isValid($this->property_info['id'])
            : true;
        if (!$res) {
            $this->addValidationError($this->pictureUploader->getError());
        }
        return $res;
    }

    public function isEmpty()
    {
        return empty($_FILES[$this->property_info['id']]['tmp_name']);
    }

    function getPropertyVariablesToAssignTypeSpecific()
    {
        return array
        (
            'id' => $this->property_info['id'],
            'value' => $this->getDisplayValue(),
            'default_style' => key($this->property_info['styles'])
        );
    }

    public function getColumnsList()
    {
        return [
            "`{$this->property_info['id']}`",
            "`{$this->property_info['id']}_file_name`",
            "`{$this->property_info['id']}_file_size`",
            "`{$this->property_info['id']}_content_type`",
        ];
    }

    public function hasMultipleColumns()
    {
        return true;
    }

    function getSQLValue()
    {
        return '';
    }


    public function getSQLValues()
    {
        $this->pictureUploader->setKey($this->object_sid);
        $name = $this->property_info['id'];

        $result = [];

        if (!$this->isEmpty()) {
            $this->pictureUploader->setAttribute($this->property_info['id'], $_FILES[$this->property_info['id']]);
            $this->pictureUploader->upload();

            $result[$name] = "'" . $this->pictureUploader->getAttribute($name . '_file_name') . "'";
            $result[$name . '_file_name'] = $result[$name];
            $result[$name . '_file_size'] = $this->pictureUploader->getAttribute($name . '_file_size');
            $result[$name . '_content_type'] = "'" . $this->pictureUploader->getAttribute($name . '_content_type') . "'";
        } elseif ($this->deleted) {
            $result[$name] = "'" . $this->pictureUploader->getAttribute($name . '_file_name') . "'";
            $result[$name . '_file_name'] = $result[$name];
            $result[$name . '_file_size'] = $this->pictureUploader->getAttribute($name . '_file_size');
            $result[$name . '_content_type'] = "'" . $this->pictureUploader->getAttribute($name . '_content_type') . "'";
        }

        return $result;
    }

    public function getColumnDefinition()
    {
        return [
            "`{$this->property_info['id']}` VARCHAR(150) NULL",
            "`{$this->property_info['id']}_file_name` VARCHAR(150) NULL",
            "`{$this->property_info['id']}_file_size` INT NULL",
            "`{$this->property_info['id']}_content_type` VARCHAR(20) NULL",
        ];
    }

    public function getDisplayValue()
    {
        return $this->getValue();
    }

    public function getValue()
    {
        $this->fillUploaderData();
        $file = $this->pictureUploader->getFiles($this->property_info['id']);
        $file['file_url'] = isset($file[$this->property_info['id']]) ?  $file[$this->property_info['id']]['url']: $file['original']['url']; //for compatibility api v2
        $file['file_name'] = $this->pictureUploader->getAttribute($this->property_info['id'] . '_file_name');
        return $file;
    }

    public static function getFieldExtraDetails()
    {
        return array
        (
            array(
                'id' => 'width',
                'caption' => 'Width',
                'type' => 'float',
                'minimum' => '1',
                'value' => 100,
                'is_required' => true,
            ),
            array(
                'id' => 'height',
                'caption' => 'Height',
                'type' => 'float',
                'minimum' => '1',
                'value' => '100',
                'is_required' => true,
            ),
            array(
                'id' => 'storage_method',
                'caption' => 'Storage Method',
                'type' => 'list',
                'list_values' => array(
                    array(
                        'id' => 'filesystem',
                        'caption' => 'File System',
                    )
                ),
                'length' => '',
                'value' => 'filesystem',
            ),

        );
    }
}
