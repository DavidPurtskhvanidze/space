<?php
/**
 *
 *    Module: image_carousel v.7.5.0-1, (c) WorksForWeb 2005 - 2016
 *
 *    Package: image_carousel-7.5.0-1
 *    Tag: tags/7.5.0-1@19785, 2016-06-17 13:19:31
 *
 *    This file is part of the 'image_carousel' module of the WorksForWeb
 *    software. The entire content is protected by the applicable national
 *    and international copyright legislation.
 *
 */


namespace modules\image_carousel\lib\CarouselImage;

use lib\ORM\ObjectDetails;

class CarouselImageDetails extends ObjectDetails
{
    protected $tableName = 'image_carousel_images';

    public function getDetails($info)
    {
        $details = new ObjectDetails();
        $details->setDetailsInfo($this->getDetailsInfo());
        $details->setTableName($this->tableName);
        $details->setOrmObjectFactory(\App()->OrmObjectFactory);
        $details->buildPropertiesWithData($info);
        return $details;
    }

    public function getDetailsInfo()
    {
        return
            [
                [
                    'id'			=> 'sid',
                    'caption'		=> 'Id',
                    'type'			=> 'integer',
                    'length'		=> '6',
                    'is_required'	=> false,
                    'is_system'		=> true,
                    'order'			=> null,
                ],
                [
                    'id' => 'caption',
                    'caption' => 'Caption',
                    'type' => 'string',
                    'length' => '255',
                    'minimum' => '1',
                    'is_required' => true,
                    'is_system' => true,
                ],
                [
                    'id' => 'url',
                    'caption' => 'Link URL',
                    'type' => 'url',
                    'is_required' => false,
                    'is_system' => true,
                ],
                [
                    'id' => 'image',
                    'caption' => 'Image file',
                    'type' => 'picture',
                    'styles' => [
                        'thumbnail' => '150x100#',
                        'large' => '1500x500',
                    ],
                    'save_into_db' => true,
                    'is_required' => true,
                    'is_system' => true,
                    'input_template' => 'field_types^input/simple_picture.tpl'
                ],
                [
                    'id' => 'disabled',
                    'caption' => 'Disabled',
                    'type' => 'boolean',
                ],
            ];
    }
}
