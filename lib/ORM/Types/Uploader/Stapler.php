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

class Stapler extends \Codesleeve\Stapler\Stapler
{
    /**
     * Return a resizer object instance.
     *
     * @param string $type
     *
     * @return \Codesleeve\Stapler\File\Image\Resizer
     */
    public static function getResizerInstance($type)
    {
        $imagineInstance = static::getImagineInstance($type);
        if (static::$resizer === null) {
            static::$resizer = new Resizer($imagineInstance);
        } else {
            static::$resizer->setImagine($imagineInstance);
        }

        return static::$resizer;
    }
}
